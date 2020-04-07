CREATE TABLE IF NOT EXISTS	posts	(
ID	SERIAL	,
post_ID	VARCHAR(30)	NOT NULL UNIQUE,
post_attributes	JSON	NULL,
post_body	VARCHAR(255)	NOT NULL,
post_images	JSONB	NULL,
post_closed	INT	NOT NULL DEFAULT 0,
post_deleted	INT	NOT NULL DEFAULT 0,
post_access	INT	NOT NULL DEFAULT 1,
post_host	VARCHAR(30)	NOT NULL,
profile_ID	VARCHAR(30)	NOT NULL,
app_ID	VARCHAR(30)	NOT NULL,
event_ID	VARCHAR(30)	NOT NULL,
process_ID	VARCHAR(30)	NOT NULL,
time_started	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_updated	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
time_finished	TIMESTAMPTZ	NOT NULL DEFAULT NOW(),
active	INT	NOT NULL DEFAULT 1
);		
CREATE SEQUENCE posts_sequence;
ALTER SEQUENCE posts_sequence RESTART WITH 8301;		
ALTER TABLE posts ALTER COLUMN ID SET DEFAULT nextval('posts_sequence');
-- ALTER TABLE posts ADD FOREIGN KEY (profile_ID) REFERENCES profiles(profile_ID);		
-- ALTER TABLE posts ADD FOREIGN KEY (app_ID) REFERENCES apps(app_ID);		
SELECT * FROM posts;	
DROP TABLE posts;		
INSERT INTO posts (post_ID,post_attributes,post_body,post_images,post_closed,post_deleted,post_access,post_host,profile_ID,app_ID,event_ID,process_ID)		
 VALUES ('30 characters','{}','255 characters','{}','1','1','1','30 characters','30 characters','30 characters','30 characters','30 characters');		
SELECT * FROM posts;	