CREATE TABLE IF NOT EXISTS	followships	(
ID	SERIAL	,
followship_ID	VARCHAR(30)	NOT NULL UNIQUE,
followship_attributes	JSON	NULL,
followship_recipient	VARCHAR(30)	NOT NULL,
followship_sender	VARCHAR(30)	NOT NULL,
followship_status	VARCHAR(30)	NOT NULL DEFAULT 'pending',
profile_ID	VARCHAR(30)	NOT NULL,
app_ID	VARCHAR(30)	NOT NULL,
event_ID	VARCHAR(30)	NOT NULL,
process_ID	VARCHAR(30)	NOT NULL,
time_started	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_updated	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_finished	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
active	INT	NOT NULL DEFAULT 1
);		
CREATE SEQUENCE followships_sequence;		
ALTER SEQUENCE followships_sequence RESTART WITH 8301;		
ALTER TABLE followships ALTER COLUMN ID SET DEFAULT nextval('followships_sequence');
-- ALTER TABLE followships ADD FOREIGN KEY (profile_ID) REFERENCES profiles(profile_ID);
-- ALTER TABLE followships ADD FOREIGN KEY (app_ID) REFERENCES apps(app_ID);	
SELECT * FROM followships;
DROP TABLE followships;
INSERT INTO followships (followship_ID,followship_attributes,followship_recipient,followship_sender,followship_status,profile_ID,app_ID,event_ID,process_ID)		
 VALUES ('30 characters','{}','30 characters','30 characters','30 characters','30 characters','30 characters','30 characters','30 characters');		
SELECT * FROM followships;

------------

CREATE TABLE IF NOT EXISTS	groups	(
ID	SERIAL	,
group_ID	VARCHAR(30)	NOT NULL UNIQUE,
group_attributes	JSON	NULL,
group_title	VARCHAR(255)	NOT NULL,
group_headline	TEXT	NULL,
group_access	INT	NOT NULL,
group_participants	JSONB	NULL,
group_images	JSONB	NULL,
group_author	VARCHAR(30)	not NULL,
profile_ID	VARCHAR(30)	NOT NULL,
app_ID	VARCHAR(30)	NOT NULL,
event_ID	VARCHAR(30)	NOT NULL,
process_ID	VARCHAR(30)	NOT NULL,
time_started	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_updated	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_finished	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
active	INT	NOT NULL DEFAULT 1
);
CREATE SEQUENCE groups_sequence;
ALTER SEQUENCE groups_sequence RESTART WITH 8301;
ALTER TABLE groups ALTER COLUMN ID SET DEFAULT nextval('groups_sequence');		
-- ALTER TABLE groups ADD FOREIGN KEY (profile_ID) REFERENCES profiles(profile_ID);		
-- ALTER TABLE groups ADD FOREIGN KEY (app_ID) REFERENCES apps(app_ID);		
SELECT * FROM groups;
DROP TABLE groups;
INSERT INTO groups (group_ID,group_attributes,group_title,group_headline,group_access,group_participants,group_images,group_author,profile_ID,app_ID,event_ID,process_ID)
 VALUES ('30-characters','{}','255 characters','lorem ipsum','1','{}','{}','30 characters','30 characters','30 characters','30 characters','30 characters');
 SELECT * FROM groups;