-- public.followships definition

-- Drop table

-- DROP TABLE public.followships;

CREATE TABLE public.followships (
	id int4 NOT NULL DEFAULT nextval('followships_sequence'::regclass),
	followship_id varchar(30) NOT NULL,
	followship_attributes json NULL,
	followship_recipient varchar(30) NOT NULL,
	followship_sender varchar(30) NOT NULL,
	followship_status varchar(30) NOT NULL DEFAULT 'pending'::character varying,
	profile_id varchar(30) NOT NULL,
	app_id varchar(30) NOT NULL,
	event_id varchar(30) NOT NULL,
	process_id varchar(30) NOT NULL,
	time_started timestamptz NOT NULL DEFAULT now(),
	time_updated timestamptz NOT NULL DEFAULT now(),
	time_finished timestamptz NOT NULL DEFAULT now(),
	active int4 NOT NULL DEFAULT 1,
	CONSTRAINT followships_followship_id_key UNIQUE (followship_id)
);

-- Permissions

ALTER TABLE public.followships OWNER TO xbepysqkbuaijy;
GRANT ALL ON TABLE public.followships TO xbepysqkbuaijy;


-- public."groups" definition

-- Drop table

-- DROP TABLE public."groups";

CREATE TABLE public."groups" (
	id int4 NOT NULL DEFAULT nextval('groups_sequence'::regclass),
	group_id varchar(30) NOT NULL,
	group_attributes json NULL,
	group_title varchar(255) NOT NULL,
	group_headline text NULL,
	group_access int4 NOT NULL,
	group_participants jsonb NULL,
	group_images jsonb NULL,
	group_author varchar(30) NOT NULL,
	profile_id varchar(30) NOT NULL,
	app_id varchar(30) NOT NULL,
	event_id varchar(30) NOT NULL,
	process_id varchar(30) NOT NULL,
	time_started timestamptz NOT NULL DEFAULT now(),
	time_updated timestamptz NOT NULL DEFAULT now(),
	time_finished timestamptz NOT NULL DEFAULT now(),
	active int4 NOT NULL DEFAULT 1,
	CONSTRAINT groups_group_id_key UNIQUE (group_id)
);

-- Permissions

ALTER TABLE public."groups" OWNER TO xbepysqkbuaijy;
GRANT ALL ON TABLE public."groups" TO xbepysqkbuaijy;





INSERT INTO public.followships (followship_id,followship_attributes,followship_recipient,followship_sender,followship_status,profile_id,app_id,event_id,process_id,time_started,time_updated,time_finished,active) VALUES
	 ('fol_7ed8024590223',NULL,'prf_tamikahollis','prf_adolphusnolan','accepted','prf_adolphusnolan','app_thentrlco','obj_4cc85a5bc4a80','obj_39fde4be05a5a','2020-04-07 19:00:55.802122-05','2020-04-07 19:00:55.802122-05','2020-04-07 19:00:55.802122-05',1),
	 ('fol_7e932b08e0c80',NULL,'prf_adolphusnolan','prf_tamikahollis','accepted','prf_tamikahollis','app_thentrlco','obj_0ee65b2bf0747','obj_d9fe93b622eef','2020-04-07 19:01:51.279441-05','2020-04-07 19:01:51.279441-05','2020-04-07 19:01:51.279441-05',1),
	 ('fol_fb5d20370581a',NULL,'prf_valencia','prf_adolphusnolan','pending','prf_adolphusnolan','app_thentrlco','evt_0906ebe02cf04','prc_27fe16f40a48c','2020-04-12 18:08:25.950465-05','2020-04-12 18:08:25.950465-05','2020-04-12 18:08:25.950465-05',1),
	 ('fol_7ae43e46d86e2',NULL,'prf_rogercraig','prf_adolphusnolan','pending','prf_adolphusnolan','app_thentrlco','evt_d460de4e80456','prc_96de578021964','2020-04-12 18:09:21.604982-05','2020-04-12 18:09:21.604982-05','2020-04-12 18:09:21.604982-05',1),
	 ('fol_bee0e5b3ac61e',NULL,'prf_leahnolan','prf_adolphusnolan','accepted','prf_adolphusnolan','app_thentrlco','evt_521757750522a','prc_79d1708bc6300','2020-04-13 00:11:20.397521-05','2020-04-13 00:11:20.397521-05','2020-04-13 00:11:20.397521-05',1),
	 ('fol_c5a90d6240192',NULL,'prf_livnolan','prf_adolphusnolan','accepted','prf_adolphusnolan','app_thentrlco','evt_619e9fe8022a2','prc_fbaea1aef726c','2020-04-12 18:16:02.007068-05','2020-04-12 18:16:02.00y7ftdzw


	 INSERT INTO public."groups" (group_id,group_attributes,group_title,group_headline,group_access,group_participants,group_images,group_author,profile_id,app_id,event_id,process_id,time_started,time_updated,time_finished,active) VALUES
	 ('grp_17cef30d8aee2',NULL,'4R Architects','RDS, ReactPHP, ReactJS & React Native',3,'{"participants": ["prf_adolphusnolan", "prf_tamikahollis", "prf_livenolan"], "administrators": ["prf_adolphusnolan", "prf_tamikahollis"]}','{"cover": "img_coverphoto", "general": ["img_0001", "img_0002", "img_0003", "img_0004", "img_0005"], "profile": "img_4rprofileimage"}','prf_adolphusnolan','prf_adolphusnolan','app_thentrlco','evt_5bc91ba8a2a6c','prc_4c2648607549d','2020-04-07 19:30:11.816143-05','2020-04-07 19:30:11.816143-05','2020-04-07 19:30:11.816143-05',1),
	 ('grp_a8f6bb510492a',NULL,'I Love Jeeps','Jeeps are fucking amazing',0,'{"contributors": ["prf_adolphus", "prf_marco", "prf_cliff", "prfclyde", "prf_kayla", "prf_lenzy"], "administrator": ["prf_adolphus", "prf_marco"]}','{}','prf_adolphusnolan','prf_adolphusnolan','app_thentrlco','evt_6233ed07d60ac','prc_0ffbc46a7e4b8','2020-04-17 19:42:02.370353-05','2020-04-17 19:42:02.370353-05','2020-04-17 19:42:02.370353-05',1);

