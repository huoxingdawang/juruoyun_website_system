ALTER TABLE `general_users` CHANGE COLUMN `type` `type` JSON NULL AFTER `id`;
INSERT INTO juruoyun_.general_users (`id`,`tel`,`mail`,`lasttime`,`name`,`password`,`enroldate`,`greendate`,`logdate`,`language`,`use`,`sex`,`zhushi`,`green_money`,`tel_show`,`mail_show`,`ip_show`,`word_special_fact`,`follow_mouth`) SELECT `id`,`tel`,`mail`,`lasttime`,`name`,`password`,`enroldate`,`greendate`,`logdate`,`language`,`use`,`sex`,`zhushi`,`green_money`,`tel_show`,`mail_show`,`ip_show`,`word_special_fact`,`follow_mouth` FROM juruoyun.general_users;
UPDATE juruoyun_.general_users SET `type`='[4]';
ALTER TABLE `general_users` CHANGE COLUMN `type` `type` JSON NOT NULL AFTER `id`;
UPDATE juruoyun_.general_users SET `order`=(SELECT MIN(`order`) FROM juruoyun_.manage_competence WHERE `type` IN (SUBSTRING_INDEX(SUBSTRING(JSON_UNQUOTE(general_users.type),2),']',1)));
UPDATE `juruoyun_`.`general_users` SET `head`='{"type": "default_head_man"}' WHERE  `sex`=1 OR `sex`=2;
UPDATE `juruoyun_`.`general_users` SET `head`='{"type": "default_head_woman"}' WHERE  `sex`=0;

INSERT INTO juruoyun_.online_judge_logs (`log_id`,`id`,`question_id`,`time`,`ans`,`result`,`testconfig`) SELECT `ojlogid`,`id`,`ojquestionid`,`time`,`logans`,JSON_OBJECT('result',`result`),JSON_OBJECT('mode',`testmode`) FROM juruoyun.oj_logs;

INSERT INTO juruoyun_.online_judge_error (`error_id`,`id`,`question_id`,`lasttime`,`times`,`maxtimes`,`extern`) SELECT `ojerrorid`,`id`,`ojquestionid`,`lasttime`,`times`,`maxtimes`,JSON_OBJECT('nexttime',`nexttime`)FROM juruoyun.oj_error;