			var _createClass = function() {
				function defineProperties(target, props) {
					for (var i = 0; i < props.length; i++) {
						var descriptor = props[i];
						descriptor.enumerable = descriptor.enumerable || false;
						descriptor.configurable = true;
						if ("value"in descriptor)
							descriptor.writable = true;
						Object.defineProperty(target, descriptor.key, descriptor);
					}
				}
				return function(Constructor, protoProps, staticProps) {
					if (protoProps)
						defineProperties(Constructor.prototype, protoProps);
					if (staticProps)
						defineProperties(Constructor, staticProps);
					return Constructor;
				}
				;
			}();		
var flvjs=new function()
{
	this.creat_player=function(mediaDataSource, optionalConfig)
	{
				function FlvPlayer(mediaDataSource, config) {

					this.TAG = 'FlvPlayer';
					this._type = 'FlvPlayer';
//					this._emitter = new _events2.default();

//					this._config = (0,_config.createDefaultConfig)();
//					if ((typeof config === 'undefined' ? 'undefined' : _typeof(config)) === 'object') {
//						Object.assign(this._config, config);
//					}

					if (mediaDataSource.type.toLowerCase() !== 'flv') {
						throw new _exception.InvalidArgumentException('FlvPlayer requires an flv MediaDataSource input!');
					}

					if (mediaDataSource.isLive === true) {
						this._config.isLive = true;
					}

/*					this.e = {
						onvLoadedMetadata: this._onvLoadedMetadata.bind(this),
						onvSeeking: this._onvSeeking.bind(this),
						onvCanPlay: this._onvCanPlay.bind(this),
						onvStalled: this._onvStalled.bind(this),
						onvProgress: this._onvProgress.bind(this)
					};*/

					if (self.performance && self.performance.now) {
						this._now = self.performance.now.bind(self.performance);
					} else {
						this._now = Date.now;
					}

					this._pendingSeekTime = null;
					// in seconds
					this._requestSetTime = false;
					this._seekpointRecord = null;
					this._progressChecker = null;

					this._mediaDataSource = mediaDataSource;
					this._mediaElement = null;
					this._msectl = null;
					this._transmuxer = null;

					this._mseSourceOpened = false;
					this._hasPendingLoad = false;
					this._receivedCanPlay = false;

					this._mediaInfo = null;
					this._statisticsInfo = null;

//					var chromeNeedIDRFix = _browser2.default.chrome && (_browser2.default.version.major < 50 || _browser2.default.version.major === 50 && _browser2.default.version.build < 2661);
//					this._alwaysSeekKeyframe = chromeNeedIDRFix || _browser2.default.msedge || _browser2.default.msie ? true : false;
					this._alwaysSeekKeyframe=false;
					if (this._alwaysSeekKeyframe) {
						this._config.accurateSeek = false;
					}
				}








				_createClass(FlvPlayer, [{
					key: 'destroy',
					value: function destroy() {
						if (this._progressChecker != null) {
							window.clearInterval(this._progressChecker);
							this._progressChecker = null;
						}
						if (this._transmuxer) {
							this.unload();
						}
						if (this._mediaElement) {
							this.detachMediaElement();
						}
						this.e = null;
						this._mediaDataSource = null;

						this._emitter.removeAllListeners();
						this._emitter = null;
					}
				}, {
					key: 'on',
					value: function on(event, listener) {
						var _this = this;

						if (event === _playerEvents2.default.MEDIA_INFO) {
							if (this._mediaInfo != null) {
								Promise.resolve().then(function() {
									_this._emitter.emit(_playerEvents2.default.MEDIA_INFO, _this.mediaInfo);
								});
							}
						} else if (event === _playerEvents2.default.STATISTICS_INFO) {
							if (this._statisticsInfo != null) {
								Promise.resolve().then(function() {
									_this._emitter.emit(_playerEvents2.default.STATISTICS_INFO, _this.statisticsInfo);
								});
							}
						}
						this._emitter.addListener(event, listener);
					}
				}, {
					key: 'off',
					value: function off(event, listener) {
						this._emitter.removeListener(event, listener);
					}
				}, {
					key: 'attachMediaElement',
					value: function attachMediaElement(mediaElement) {
						var _this2 = this;

						this._mediaElement = mediaElement;
						mediaElement.addEventListener('loadedmetadata', this.e.onvLoadedMetadata);
						mediaElement.addEventListener('seeking', this.e.onvSeeking);
						mediaElement.addEventListener('canplay', this.e.onvCanPlay);
						mediaElement.addEventListener('stalled', this.e.onvStalled);
						mediaElement.addEventListener('progress', this.e.onvProgress);

						this._msectl = new _mseController2.default(this._config);

						this._msectl.on(_mseEvents2.default.UPDATE_END, this._onmseUpdateEnd.bind(this));
						this._msectl.on(_mseEvents2.default.BUFFER_FULL, this._onmseBufferFull.bind(this));
						this._msectl.on(_mseEvents2.default.SOURCE_OPEN, function() {
							_this2._mseSourceOpened = true;
							if (_this2._hasPendingLoad) {
								_this2._hasPendingLoad = false;
								_this2.load();
							}
						});
						this._msectl.on(_mseEvents2.default.ERROR, function(info) {
							_this2._emitter.emit(_playerEvents2.default.ERROR, _playerErrors.ErrorTypes.MEDIA_ERROR, _playerErrors.ErrorDetails.MEDIA_MSE_ERROR, info);
						});

						this._msectl.attachMediaElement(mediaElement);

						if (this._pendingSeekTime != null) {
							try {
								mediaElement.currentTime = this._pendingSeekTime;
								this._pendingSeekTime = null;
							} catch (e) {// IE11 may throw InvalidStateError if readyState === 0
							// We can defer set currentTime operation after loadedmetadata
							}
						}
					}
				}, {
					key: 'detachMediaElement',
					value: function detachMediaElement() {
						if (this._mediaElement) {
							this._msectl.detachMediaElement();
							this._mediaElement.removeEventListener('loadedmetadata', this.e.onvLoadedMetadata);
							this._mediaElement.removeEventListener('seeking', this.e.onvSeeking);
							this._mediaElement.removeEventListener('canplay', this.e.onvCanPlay);
							this._mediaElement.removeEventListener('stalled', this.e.onvStalled);
							this._mediaElement.removeEventListener('progress', this.e.onvProgress);
							this._mediaElement = null;
						}
						if (this._msectl) {
							this._msectl.destroy();
							this._msectl = null;
						}
					}
				}, {
					key: 'load',
					value: function load() {
						var _this3 = this;

						if (!this._mediaElement) {
							throw new _exception.IllegalStateException('HTMLMediaElement must be attached before load()!');
						}
						if (this._transmuxer) {
							throw new _exception.IllegalStateException('FlvPlayer.load() has been called, please call unload() first!');
						}
						if (this._hasPendingLoad) {
							return;
						}

						if (this._config.deferLoadAfterSourceOpen && this._mseSourceOpened === false) {
							this._hasPendingLoad = true;
							return;
						}

						if (this._mediaElement.readyState > 0) {
							this._requestSetTime = true;
							// IE11 may throw InvalidStateError if readyState === 0
							this._mediaElement.currentTime = 0;
						}

						this._transmuxer = new _transmuxer2.default(this._mediaDataSource,this._config);

						this._transmuxer.on(_transmuxingEvents2.default.INIT_SEGMENT, function(type, is) {
							_this3._msectl.appendInitSegment(is);
						});
						this._transmuxer.on(_transmuxingEvents2.default.MEDIA_SEGMENT, function(type, ms) {
							_this3._msectl.appendMediaSegment(ms);

							// lazyLoad check
							if (_this3._config.lazyLoad && !_this3._config.isLive) {
								var currentTime = _this3._mediaElement.currentTime;
								if (ms.info.endDts >= (currentTime + _this3._config.lazyLoadMaxDuration) * 1000) {
									if (_this3._progressChecker == null) {
										_logger2.default.v(_this3.TAG, 'Maximum buffering duration exceeded, suspend transmuxing task');
										_this3._suspendTransmuxer();
									}
								}
							}
						});
						this._transmuxer.on(_transmuxingEvents2.default.LOADING_COMPLETE, function() {
							_this3._msectl.endOfStream();
							_this3._emitter.emit(_playerEvents2.default.LOADING_COMPLETE);
						});
						this._transmuxer.on(_transmuxingEvents2.default.RECOVERED_EARLY_EOF, function() {
							_this3._emitter.emit(_playerEvents2.default.RECOVERED_EARLY_EOF);
						});
						this._transmuxer.on(_transmuxingEvents2.default.IO_ERROR, function(detail, info) {
							_this3._emitter.emit(_playerEvents2.default.ERROR, _playerErrors.ErrorTypes.NETWORK_ERROR, detail, info);
						});
						this._transmuxer.on(_transmuxingEvents2.default.DEMUX_ERROR, function(detail, info) {
							_this3._emitter.emit(_playerEvents2.default.ERROR, _playerErrors.ErrorTypes.MEDIA_ERROR, detail, {
								code: -1,
								msg: info
							});
						});
						this._transmuxer.on(_transmuxingEvents2.default.MEDIA_INFO, function(mediaInfo) {
							_this3._mediaInfo = mediaInfo;
							_this3._emitter.emit(_playerEvents2.default.MEDIA_INFO, Object.assign({}, mediaInfo));
						});
						this._transmuxer.on(_transmuxingEvents2.default.STATISTICS_INFO, function(statInfo) {
							_this3._statisticsInfo = _this3._fillStatisticsInfo(statInfo);
							_this3._emitter.emit(_playerEvents2.default.STATISTICS_INFO, Object.assign({}, _this3._statisticsInfo));
						});
						this._transmuxer.on(_transmuxingEvents2.default.RECOMMEND_SEEKPOINT, function(milliseconds) {
							if (_this3._mediaElement && !_this3._config.accurateSeek) {
								_this3._requestSetTime = true;
								_this3._mediaElement.currentTime = milliseconds / 1000;
							}
						});

						this._transmuxer.open();
					}
				}, {
					key: 'unload',
					value: function unload() {
						if (this._mediaElement) {
							this._mediaElement.pause();
						}
						if (this._msectl) {
							this._msectl.seek(0);
						}
						if (this._transmuxer) {
							this._transmuxer.close();
							this._transmuxer.destroy();
							this._transmuxer = null;
						}
					}
				}, {
					key: 'play',
					value: function play() {
						return this._mediaElement.play();
					}
				}, {
					key: 'pause',
					value: function pause() {
						this._mediaElement.pause();
					}
				}, {
					key: '_fillStatisticsInfo',
					value: function _fillStatisticsInfo(statInfo) {
						statInfo.playerType = this._type;

						if (!(this._mediaElement instanceof HTMLVideoElement)) {
							return statInfo;
						}

						var hasQualityInfo = true;
						var decoded = 0;
						var dropped = 0;

						if (this._mediaElement.getVideoPlaybackQuality) {
							var quality = this._mediaElement.getVideoPlaybackQuality();
							decoded = quality.totalVideoFrames;
							dropped = quality.droppedVideoFrames;
						} else if (this._mediaElement.webkitDecodedFrameCount != undefined) {
							decoded = this._mediaElement.webkitDecodedFrameCount;
							dropped = this._mediaElement.webkitDroppedFrameCount;
						} else {
							hasQualityInfo = false;
						}

						if (hasQualityInfo) {
							statInfo.decodedFrames = decoded;
							statInfo.droppedFrames = dropped;
						}

						return statInfo;
					}
				}, {
					key: '_onmseUpdateEnd',
					value: function _onmseUpdateEnd() {
						if (!this._config.lazyLoad || this._config.isLive) {
							return;
						}

						var buffered = this._mediaElement.buffered;
						var currentTime = this._mediaElement.currentTime;
						var currentRangeStart = 0;
						var currentRangeEnd = 0;

						for (var i = 0; i < buffered.length; i++) {
							var start = buffered.start(i);
							var end = buffered.end(i);
							if (start <= currentTime && currentTime < end) {
								currentRangeStart = start;
								currentRangeEnd = end;
								break;
							}
						}

						if (currentRangeEnd >= currentTime + this._config.lazyLoadMaxDuration && this._progressChecker == null) {
							_logger2.default.v(this.TAG, 'Maximum buffering duration exceeded, suspend transmuxing task');
							this._suspendTransmuxer();
						}
					}
				}, {
					key: '_onmseBufferFull',
					value: function _onmseBufferFull() {
						_logger2.default.v(this.TAG, 'MSE SourceBuffer is full, suspend transmuxing task');
						if (this._progressChecker == null) {
							this._suspendTransmuxer();
						}
					}
				}, {
					key: '_suspendTransmuxer',
					value: function _suspendTransmuxer() {
						if (this._transmuxer) {
							this._transmuxer.pause();

							if (this._progressChecker == null) {
								this._progressChecker = window.setInterval(this._checkProgressAndResume.bind(this), 1000);
							}
						}
					}
				}, {
					key: '_checkProgressAndResume',
					value: function _checkProgressAndResume() {
						var currentTime = this._mediaElement.currentTime;
						var buffered = this._mediaElement.buffered;

						var needResume = false;

						for (var i = 0; i < buffered.length; i++) {
							var from = buffered.start(i);
							var to = buffered.end(i);
							if (currentTime >= from && currentTime < to) {
								if (currentTime >= to - this._config.lazyLoadRecoverDuration) {
									needResume = true;
								}
								break;
							}
						}

						if (needResume) {
							window.clearInterval(this._progressChecker);
							this._progressChecker = null;
							if (needResume) {
								_logger2.default.v(this.TAG, 'Continue loading from paused position');
								this._transmuxer.resume();
							}
						}
					}
				}, {
					key: '_isTimepointBuffered',
					value: function _isTimepointBuffered(seconds) {
						var buffered = this._mediaElement.buffered;

						for (var i = 0; i < buffered.length; i++) {
							var from = buffered.start(i);
							var to = buffered.end(i);
							if (seconds >= from && seconds < to) {
								return true;
							}
						}
						return false;
					}
				}, {
					key: '_internalSeek',
					value: function _internalSeek(seconds) {
						var directSeek = this._isTimepointBuffered(seconds);

						var directSeekBegin = false;
						var directSeekBeginTime = 0;

						if (seconds < 1.0 && this._mediaElement.buffered.length > 0) {
							var videoBeginTime = this._mediaElement.buffered.start(0);
							if (videoBeginTime < 1.0 && seconds < videoBeginTime || _browser2.default.safari) {
								directSeekBegin = true;
								// also workaround for Safari: Seek to 0 may cause video stuck, use 0.1 to avoid
								directSeekBeginTime = _browser2.default.safari ? 0.1 : videoBeginTime;
							}
						}

						if (directSeekBegin) {
							// seek to video begin, set currentTime directly if beginPTS buffered
							this._requestSetTime = true;
							this._mediaElement.currentTime = directSeekBeginTime;
						} else if (directSeek) {
							// buffered position
							if (!this._alwaysSeekKeyframe) {
								this._requestSetTime = true;
								this._mediaElement.currentTime = seconds;
							} else {
								var idr = this._msectl.getNearestKeyframe(Math.floor(seconds * 1000));
								this._requestSetTime = true;
								if (idr != null) {
									this._mediaElement.currentTime = idr.dts / 1000;
								} else {
									this._mediaElement.currentTime = seconds;
								}
							}
							if (this._progressChecker != null) {
								this._checkProgressAndResume();
							}
						} else {
							if (this._progressChecker != null) {
								window.clearInterval(this._progressChecker);
								this._progressChecker = null;
							}
							this._msectl.seek(seconds);
							this._transmuxer.seek(Math.floor(seconds * 1000));
							// in milliseconds
							// no need to set mediaElement.currentTime if non-accurateSeek,
							// just wait for the recommend_seekpoint callback
							if (this._config.accurateSeek) {
								this._requestSetTime = true;
								this._mediaElement.currentTime = seconds;
							}
						}
					}
				}, {
					key: '_checkAndApplyUnbufferedSeekpoint',
					value: function _checkAndApplyUnbufferedSeekpoint() {
						if (this._seekpointRecord) {
							if (this._seekpointRecord.recordTime <= this._now() - 100) {
								var target = this._mediaElement.currentTime;
								this._seekpointRecord = null;
								if (!this._isTimepointBuffered(target)) {
									if (this._progressChecker != null) {
										window.clearTimeout(this._progressChecker);
										this._progressChecker = null;
									}
									// .currentTime is consists with .buffered timestamp
									// Chrome/Edge use DTS, while FireFox/Safari use PTS
									this._msectl.seek(target);
									this._transmuxer.seek(Math.floor(target * 1000));
									// set currentTime if accurateSeek, or wait for recommend_seekpoint callback
									if (this._config.accurateSeek) {
										this._requestSetTime = true;
										this._mediaElement.currentTime = target;
									}
								}
							} else {
								window.setTimeout(this._checkAndApplyUnbufferedSeekpoint.bind(this), 50);
							}
						}
					}
				}, {
					key: '_checkAndResumeStuckPlayback',
					value: function _checkAndResumeStuckPlayback(stalled) {
						var media = this._mediaElement;
						if (stalled || !this._receivedCanPlay || media.readyState < 2) {
							// HAVE_CURRENT_DATA
							var buffered = media.buffered;
							if (buffered.length > 0 && media.currentTime < buffered.start(0)) {
								_logger2.default.w(this.TAG, 'Playback seems stuck at ' + media.currentTime + ', seek to ' + buffered.start(0));
								this._requestSetTime = true;
								this._mediaElement.currentTime = buffered.start(0);
								this._mediaElement.removeEventListener('progress', this.e.onvProgress);
							}
						} else {
							// Playback didn't stuck, remove progress event listener
							this._mediaElement.removeEventListener('progress', this.e.onvProgress);
						}
					}
				}, {
					key: '_onvLoadedMetadata',
					value: function _onvLoadedMetadata(e) {
						if (this._pendingSeekTime != null) {
							this._mediaElement.currentTime = this._pendingSeekTime;
							this._pendingSeekTime = null;
						}
					}
				}, {
					key: '_onvSeeking',
					value: function _onvSeeking(e) {
						// handle seeking request from browser's progress bar
						var target = this._mediaElement.currentTime;
						var buffered = this._mediaElement.buffered;

						if (this._requestSetTime) {
							this._requestSetTime = false;
							return;
						}

						if (target < 1.0 && buffered.length > 0) {
							// seek to video begin, set currentTime directly if beginPTS buffered
							var videoBeginTime = buffered.start(0);
							if (videoBeginTime < 1.0 && target < videoBeginTime || _browser2.default.safari) {
								this._requestSetTime = true;
								// also workaround for Safari: Seek to 0 may cause video stuck, use 0.1 to avoid
								this._mediaElement.currentTime = _browser2.default.safari ? 0.1 : videoBeginTime;
								return;
							}
						}

						if (this._isTimepointBuffered(target)) {
							if (this._alwaysSeekKeyframe) {
								var idr = this._msectl.getNearestKeyframe(Math.floor(target * 1000));
								if (idr != null) {
									this._requestSetTime = true;
									this._mediaElement.currentTime = idr.dts / 1000;
								}
							}
							if (this._progressChecker != null) {
								this._checkProgressAndResume();
							}
							return;
						}

						this._seekpointRecord = {
							seekPoint: target,
							recordTime: this._now()
						};
						window.setTimeout(this._checkAndApplyUnbufferedSeekpoint.bind(this), 50);
					}
				}, {
					key: '_onvCanPlay',
					value: function _onvCanPlay(e) {
						this._receivedCanPlay = true;
						this._mediaElement.removeEventListener('canplay', this.e.onvCanPlay);
					}
				}, {
					key: '_onvStalled',
					value: function _onvStalled(e) {
						this._checkAndResumeStuckPlayback(true);
					}
				}, {
					key: '_onvProgress',
					value: function _onvProgress(e) {
						this._checkAndResumeStuckPlayback();
					}
				}, {
					key: 'type',
					get: function get() {
						return this._type;
					}
				}, {
					key: 'buffered',
					get: function get() {
						return this._mediaElement.buffered;
					}
				}, {
					key: 'duration',
					get: function get() {
						return this._mediaElement.duration;
					}
				}, {
					key: 'volume',
					get: function get() {
						return this._mediaElement.volume;
					},
					set: function set(value) {
						this._mediaElement.volume = value;
					}
				}, {
					key: 'muted',
					get: function get() {
						return this._mediaElement.muted;
					},
					set: function set(muted) {
						this._mediaElement.muted = muted;
					}
				}, {
					key: 'currentTime',
					get: function get() {
						if (this._mediaElement) {
							return this._mediaElement.currentTime;
						}
						return 0;
					},
					set: function set(seconds) {
						if (this._mediaElement) {
							this._internalSeek(seconds);
						} else {
							this._pendingSeekTime = seconds;
						}
					}
				}, {
					key: 'mediaInfo',
					get: function get() {
						return Object.assign({}, this._mediaInfo);
					}
				}, {
					key: 'statisticsInfo',
					get: function get() {
						if (this._statisticsInfo == null) {
							this._statisticsInfo = {};
						}
						this._statisticsInfo = this._fillStatisticsInfo(this._statisticsInfo);
						return Object.assign({}, this._statisticsInfo);
					}
				}]);

				return FlvPlayer(mediaDataSource,optionalConfig);
	}
};
