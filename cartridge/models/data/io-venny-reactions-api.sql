CREATE TABLE IF NOT EXISTS	acknowledgements	(
ID	SERIAL	,
acknowledgement_ID	VARCHAR(30)	NOT NULL UNIQUE,
acknowledgement_attributes	JSON	NULL,
acknowledgement_type	VARCHAR(30)	NOT NULL,
acknowledgement_object	VARCHAR(30)	NOT NULL,
profile_ID	VARCHAR(30)	NOT NULL,
app_ID	VARCHAR(30)	NOT NULL,
event_ID	VARCHAR(30)	NOT NULL,
process_ID	VARCHAR(30)	NOT NULL,
time_started	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_updated	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_finished	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
active	INT	NOT NULL DEFAULT 1
);		
CREATE SEQUENCE acknowledgements_sequence;		
ALTER SEQUENCE acknowledgements_sequence RESTART WITH 8301;		
ALTER TABLE acknowledgements ALTER COLUMN ID SET DEFAULT nextval('acknowledgements_sequence');
-- ALTER TABLE acknowledgements ADD FOREIGN KEY (profile_ID) REFERENCES profiles(profile_ID);
-- ALTER TABLE acknowledgements ADD FOREIGN KEY (app_ID) REFERENCES apps(app_ID);
SELECT * FROM acknowledgements;
DROP TABLE acknowledgements;	
INSERT INTO acknowledgements (acknowledgement_ID,acknowledgement_attributes,acknowledgement_type,acknowledgement_object,profile_ID,app_ID,event_ID,process_ID)		
 VALUES ('30 characters','{}','30 characters','30 characters','30 characters','30 characters','30 characters','30 characters');		
SELECT * FROM acknowledgements;

CREATE TABLE IF NOT EXISTS	comments	(
ID	SERIAL	,
comment_ID	VARCHAR(30)	NOT NULL UNIQUE,
comment_attributes	JSON	NULL,
comment_text	TEXT	NOT NULL,
comment_thread	VARCHAR(30)	NULL,
comment_object	VARCHAR(30)	NOT NULL,
profile_ID	VARCHAR(30)	NOT NULL,
app_ID	VARCHAR(30)	NOT NULL,
event_ID	VARCHAR(30)	NOT NULL,
process_ID	VARCHAR(30)	NOT NULL,
time_started	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_updated	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_finished	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
active	INT	NOT NULL DEFAULT 1
);
CREATE SEQUENCE comments_sequence;	
ALTER SEQUENCE comments_sequence RESTART WITH 8301;
ALTER TABLE comments ALTER COLUMN ID SET DEFAULT nextval('comments_sequence');
-- ALTER TABLE comments ADD FOREIGN KEY (profile_ID) REFERENCES profiles(profile_ID);
-- ALTER TABLE comments ADD FOREIGN KEY (app_ID) REFERENCES apps(app_ID);		
SELECT * FROM comments;		
DROP TABLE comments;		
INSERT INTO comments (comment_ID,comment_attributes,comment_text,comment_thread,comment_object,profile_ID,app_ID,event_ID,process_ID)		
 VALUES ('30-characters','{}','lorem ipsum','30 characters','30 characters','30 characters','30 characters','30 characters','30 characters');		
SELECT * FROM comments;
SELECT * FROM comments;