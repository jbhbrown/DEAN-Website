-- TODO: Put ALL SQL in between `BEGIN TRANSACTION` and `COMMIT`
BEGIN TRANSACTION;

-- TODO: create tables

CREATE TABLE `users` (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	username TEXT NOT NULL UNIQUE,
    password TEXT NOT NULL
);

CREATE TABLE `images` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`file_name` TEXT NOT NULL UNIQUE,
    `citation` TEXT NOT NULL,
    `file_ext` TEXT NOT NULL,
    `description` TEXT NOT NULL,
    `user_id` TEXT NOT NULL
);

CREATE TABLE `tags` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`tag` TEXT UNIQUE
);

CREATE TABLE `image_tags` (
	`id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	`image_id` TEXT NOT NULL,
    `tag_id` TEXT NOT NULL
);

CREATE TABLE sessions (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT UNIQUE,
	user_id INTEGER NOT NULL,
	session TEXT NOT NULL UNIQUE
);


-- TODO: initial seed data

INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_bonnie.png','https://www.wattpad.com/400338311-bonnie-and-clyde-~-we%27re-never-just-friends','png','Bonnie and Clyde shoot','sur4n');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_bricks.jpg','http://k-popped.com/2016/08/koreas-rising-rb-singer-songwriter-dean-is-coming-to-malaysia/','jpg','Standing by brick wall','sur4n');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_burberry.png','https://weheartit.com/entry/307470538','png','Wearing all Burberry','zic0');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_crush.jpg','https://twitter.com/deanperu_/status/772132930692927489','jpg','Dean with Crush','zic0');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_fox.jpg','https://www.youtube.com/watch?v=gmsKUUZZ64Y','jpg','Wearing fox sweater','sur4n');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_headband.jpg','https://www.pinterest.co.uk/pin/614600680371476567/','jpg','Wearing a headband','sur4n');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_hearts.jpg','https://www.pinterest.com/pin/359373245251390272/','jpg','With hearts','sur4n');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_heize.jpg','https://aminoapps.com/c/rpgkpop-com/page/blog/deantrbl/RZE6_4BfwuwQWqeE6Za0Gv2LL1EkRQKW3R','jpg','With Heize','sur4n');
-- INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_leather.png','https://aznmodern.com/2017/02/03/korean-rb-artist-dean-makes-first-canadian-appearances/','png','Wearing leather jacket','zic0');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_lights.jpg','http://deantheofficial.tumblr.com/page/93','jpg','Dean with stage lights','zic0');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_mic.jpg','https://www.flickr.com/photos/95554931@N04/28339500842','jpg','Dean with microphone','zic0');
INSERT INTO `images` (file_name, citation, file_ext, description,  user_id) VALUES ('dean_tv.jpg','https://www.pinterest.com/pin/398287160787174970/?lp=true ','jpg','Dean with a TV','zic0');


INSERT INTO `tags` (tag) VALUES ('neon');--1
INSERT INTO `tags` (tag) VALUES ('dark');--2
INSERT INTO `tags` (tag) VALUES ('bricks');--3
INSERT INTO `tags` (tag) VALUES ('shirt');	--4
INSERT INTO `tags` (tag) VALUES ('headband');--5
INSERT INTO `tags` (tag) VALUES ('burberry');--6
INSERT INTO `tags` (tag) VALUES ('blond');--7
INSERT INTO `tags` (tag) VALUES ('crush');--8
INSERT INTO `tags` (tag) VALUES ('fox');--9
INSERT INTO `tags` (tag) VALUES ('glasses');--10
INSERT INTO `tags` (tag) VALUES ('hearts');--11
INSERT INTO `tags` (tag) VALUES ('heize');--12
INSERT INTO `tags` (tag) VALUES ('car');--13
INSERT INTO `tags` (tag) VALUES ('leather');--14
INSERT INTO `tags` (tag) VALUES ('outside');--15
INSERT INTO `tags` (tag) VALUES ('lights');--16
INSERT INTO `tags` (tag) VALUES ('concert');--17
INSERT INTO `tags` (tag) VALUES ('tv');--18
INSERT INTO `tags` (tag) VALUES ('vintage');--19


INSERT INTO `image_tags` (image_id, tag_id) VALUES ('1','1');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('1','2');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('2','3');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('2','4');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('3','5');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('3','6');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('3','7');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('4','1');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('4','2');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('4','8');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('5','9');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('5','10');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('6','5');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('6','2');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('7','11');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('8','12');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('8','13');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('8','14');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('9','2');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('9','16');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('9','17');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('10','1');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('10','17');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('11','18');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('11','19');
INSERT INTO `image_tags` (image_id, tag_id) VALUES ('11','17');


-- TODO: FOR HASHED PASSWORDS, LEAVE A COMMENT WITH THE PLAIN TEXT PASSWORD!

INSERT INTO `users` (username, password) VALUES ('sur4n','$2y$10$sqpqz88/VMZbTIpHAS4yNuax6vZeeoEF3umDWVlv4CL47zm5P6O7.'); -- password: half110
INSERT INTO `users` (username, password) VALUES ('zic0','$2y$10$q2j4KH1.PlKo2tauBQEStOV5wnUn1hfQh5TvB3/mjBatrj9krzTX.');  -- password: cru$h92

COMMIT;
