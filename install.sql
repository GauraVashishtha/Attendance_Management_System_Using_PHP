-- Create database
CREATE DATABASE IF NOT EXISTS Campus;
use Campus;

-- Student Master Table to store table names for particular years
CREATE TABLE IF NOT EXISTS Student_Master
             (
             	year varchar(100) NOT NULL,
             	table_name varchar(100) NOT NULL,
             	PRIMARY Key(year)
             );
-- INSERT INTO Student_Master VALUES ('4','year_2016');

-- CREATE TABLE IF NOT EXISTS year_2016_<year>
-- 			(
--              	    name varchar(100) NOT NULL,
--                       roll_number varchar(100) NOT NULL,
--                       class varchar(100) NOT NULL,
--              	    PRIMARY KEY (roll_number)	
-- 			);

-- User seperate table to storer student login
CREATE TABLE IF NOT EXISTS Student_User
             (
                  roll_number varchar(200) NOT NULL,
                  pass varchar(200) NOT NULL,
                  e_mail varchar(200) NOT NULL,
                  verified varchar(200) NOT NULL,
                  PRIMARY KEY (roll_number)
             );


-- Techer Master table to 
-- map teacher credentials with techer table name
-- Registration of teacher
CREATE TABLE IF NOT EXISTS Teacher_Master
            (
                  name varchar(100) NOT NULL,
                  user_id varchar(200) NOT NULL,
                  pass varchar(200) NOT NULL,
                  department varchar(200) NOT NULL,
                  time_table varchar(400) NOT NULL,
                  log_table varchar(400) NOT NULL,
                  PRIMARY KEY (user_id)
            );

CREATE TABLE IF NOT EXISTS Admin_Master
             (
                  user_id varchar(200) NOT NULL,
                  pass varchar(200) NOT NULL,
                  department varchar(200) NOT NULL,
                  PRIMARY KEY (user_id)
             );

INSERT INTO `admin_master`(`user_id`, `pass`, `department`) VALUES ('admin','admin','*');


CREATE TABLE IF NOT EXISTS configuration
			(
				parameter varchar(200) NOT NULL,
				value varchar(200),
				PRIMARY KEY(parameter)
			);

-- SCHEME

INSERT INTO `configuration`(`parameter`, `value`) VALUES ('DEPT','CS_IT_MECH_PIE_EE_ECE');

-- INSERT INTO `configuration`(`parameter`, `value`) VALUES ('IT_1_sec','1_2_3_4_5_6');
-- INSERT INTO `configuration`(`parameter`, `value`) VALUES ('CS_1_sec','1_2_3_4_5');

INSERT INTO `configuration`(`parameter`, `value`) VALUES ('CS_1_sub','HSIR11_MAIR11_PHIR11_CHIR11_CSIR11_CSIR13_MEIR11_SWIR11');
INSERT INTO `configuration`(`parameter`, `value`) VALUES ('IT_1_sub','HSIR11_MAIR11_PHIR11_CHIR11_CSIR11_ITIR13_MEIR11_SWIR11');



-- INSERT INTO `teacher_master` (`id`, `name`, `user_id`, `pass`, `department`, `time_table`, `sheet_table`) VALUES (NULL, 'Rishi Chauhan', 'RSC123', 'RSC1234', 'CS', 'RSC123_CS_9_2019', 'RSC123_CS_9_2019_log');

-- -- 'RSC123_CS_9_2019', 'RSC123_CS_9_2019_log'

-- CREATE TABLE IF NOT EXISTS RSC123_CS_9_2019
--              (
--              	day varchar(100) NOT NULL,
--              	period varchar(100) NOT NULL,
--              	semester varchar(100) NOT NULL,
--              	subject varchar(100) NOT NULL,
--					department varchar(100) NOT NULL,
--              	section varchar(100) NOT NULL,
--              	CONSTRAINT PK_RSC123_CS_9_2019 PRIMARY KEY(day,period)
--              );
-- CREATE TABLE IF NOT EXISTS RSC123_CS_9_2019_log
--              (
--              	sheet_table varchar(100) NOT NULL,
--              	PRIMARY KEY(sheet_table)
--              );





-- CREATE TABLE IF NOT EXISTS userId_sub_dept_sem_sec
-- 			 (
-- 				id int(100) NOT NULL AUTO_INCREMENT,
--              	curr_date varchar(100) ,
--              	period varchar(100) ,
--              	R_11610321 varchar(100) ,
--              	R_11610323 varchar(100) ,
--              	R_11610324 varchar(100) ,
--              	R_11610325 varchar(100) ,
--              	R_11610326 varchar(100) ,
--              	PRIMARY KEY (id)
-- 			 );





