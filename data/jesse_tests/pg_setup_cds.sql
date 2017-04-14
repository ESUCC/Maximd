-- \i '/var/www/nebraska_survey/data/jesse_tests/pg_setup_cds.sql'
--
-- NEB COUNTY TABLE AND AUDIT TABLE
--
    drop table neb_county cascade;
    CREATE TABLE neb_county (
        id_author           integer DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,
    
        id_neb_county       Serial PRIMARY KEY,
        
        id_county           character(2) NOT NULL,
        name_county         character varying NOT NULL,
        status              character varying DEFAULT 'Active'::character varying NOT NULL
    
    );
    
    drop table neb_county_audit cascade;
    CREATE TABLE neb_county_audit (
        id_neb_county_data   	    Serial PRIMARY KEY,
            operation 		        char(1)   NOT NULL,
            stamp            		timestamp NOT NULL,
            userid            		text      NOT NULL,
            
        id_author           integer   DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,
    
        id_neb_county       integer,
        
        id_county           character(2) NOT NULL,
        name_county         character varying NOT NULL,
        status              character varying DEFAULT 'Active'::character varying NOT NULL
    );
    
    
    CREATE OR REPLACE FUNCTION neb_county_audit() RETURNS TRIGGER AS $usr_audit$
        BEGIN
            --
            -- Create a row in neb_county_audit to reflect the operation performed on MinUser,
            -- make use of the special variable TG_OP to work out the operation.
            --
            IF (TG_OP = 'DELETE') THEN
                INSERT INTO neb_county_audit VALUES (DEFAULT, 'D', now(), user, OLD.*);
                RETURN OLD;
            ELSIF (TG_OP = 'UPDATE') THEN
                INSERT INTO neb_county_audit VALUES (DEFAULT, 'U', now(), user, NEW.*);
                RETURN NEW;
            ELSIF (TG_OP = 'INSERT') THEN
                INSERT INTO neb_county_audit VALUES (DEFAULT, 'I', now(), user, NEW.*);
                RETURN NEW;
            END IF;
            RETURN NULL; -- result is ignored since this is an AFTER trigger
        END;
    $usr_audit$ LANGUAGE plpgsql;
    
    CREATE TRIGGER neb_county_audit AFTER INSERT OR UPDATE OR DELETE ON neb_county 
        FOR EACH ROW EXECUTE PROCEDURE neb_county_audit();
    
    
    Create view county as select nca.* 
        from neb_county nc left join neb_county_audit nca on nc.id_neb_county = nca.id_neb_county;
        




--
-- NEB DISTRICT TABLE AND AUDIT TABLE
--
    drop table neb_district cascade;
    CREATE TABLE neb_district (
        id_author           integer DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,
    
        id_neb_district     Serial PRIMARY KEY,
        
        id_county           character(2) NOT NULL,
        id_district         character(4) NOT NULL,
        name_district       character varying NOT NULL,
        status              character varying DEFAULT 'Inactive'::character varying,
        
        id_district_mgr integer,
        id_account_sprv integer,
        address_street1 character varying,
        address_street2 character varying,
        address_city character varying,
        address_state character(2),
        address_zip character varying,
        phone_main character varying,
        logo_flag boolean DEFAULT false,
        add_resource1 character varying DEFAULT 'Nebraska Parent Training Center: 800-284-8520 or 402-346-0525'::character varying,
        add_resource2 character varying DEFAULT 'Nebraska Advocacy Services: 800-422-6091 or 402-474-3183'::character varying,
        sch_yr_start_mth integer,
        sch_yr_start_day integer,
        sch_yr_end_month integer,
        sch_yr_end_day integer,
        dev_delay_cutoff_age integer,
        use_goal_helper boolean,
        use_form_011 boolean,
        use_form_012 boolean,
        optional_features boolean,
        approving_mgr_id character varying,
        id_district_mgr_old integer,
        id_account_sprv_old integer,
        use_sesis_snapshot boolean,
        assurance_stmt boolean,
        pref_district_imports boolean,
        district_import_code character varying,
        use_accomodations_checklist boolean,
        sesis_email character varying,
        sesis_send_tonight character varying,
        imagefile character varying,
        print_header character varying,
        use_iep_benchmarks boolean,
        fedrep_email character varying,
        fedrep_send_tonight character varying,
        use_form_019 boolean,
        use_form_020 boolean,
        use_form_021 boolean,
        report_nssrs_time_to_run character varying,
        use_nssrs boolean,
        email_nssrs character varying,
        nssrs_send_tonight character varying,
        use_nssrs_snapshot boolean

    
    );
    
    drop table neb_district_audit cascade;
    CREATE TABLE neb_district_audit (
        id_neb_district_data        Serial PRIMARY KEY,
            operation 		        char(1)   NOT NULL,
            stamp            		timestamp NOT NULL,
            userid            		text      NOT NULL,
            
        id_author                   integer NOT NULL,
        id_author_last_mod          integer NOT NULL,
        timestamp_created           timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod          timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user            integer,
        checkout_time               integer,
    
        id_neb_district         integer,
    
        id_county               character(2) NOT NULL,
        id_district             character(4) NOT NULL,
    
        name_district character varying NOT NULL,
        status character varying DEFAULT 'Inactive'::character varying,

        id_district_mgr integer,
        id_account_sprv integer,
        address_street1 character varying,
        address_street2 character varying,
        address_city character varying,
        address_state character(2),
        address_zip character varying,
        phone_main character varying,
        logo_flag boolean DEFAULT false,
        add_resource1 character varying DEFAULT 'Nebraska Parent Training Center: 800-284-8520 or 402-346-0525'::character varying,
        add_resource2 character varying DEFAULT 'Nebraska Advocacy Services: 800-422-6091 or 402-474-3183'::character varying,
        sch_yr_start_mth integer,
        sch_yr_start_day integer,
        sch_yr_end_month integer,
        sch_yr_end_day integer,
        dev_delay_cutoff_age integer,
        use_goal_helper boolean,
        use_form_011 boolean,
        use_form_012 boolean,
        optional_features boolean,
        approving_mgr_id character varying,
        id_district_mgr_old integer,
        id_account_sprv_old integer,
        use_sesis_snapshot boolean,
        assurance_stmt boolean,
        pref_district_imports boolean,
        district_import_code character varying,
        use_accomodations_checklist boolean,
        sesis_email character varying,
        sesis_send_tonight character varying,
        imagefile character varying,
        print_header character varying,
        use_iep_benchmarks boolean,
        fedrep_email character varying,
        fedrep_send_tonight character varying,
        use_form_019 boolean,
        use_form_020 boolean,
        use_form_021 boolean,
        report_nssrs_time_to_run character varying,
        use_nssrs boolean,
        email_nssrs character varying,
        nssrs_send_tonight character varying,
        use_nssrs_snapshot boolean
    );
    
    
    CREATE OR REPLACE FUNCTION neb_district_audit() RETURNS TRIGGER AS $usr_audit$
        BEGIN
            --
            -- Create a row in neb_district_audit to reflect the operation performed on MinUser,
            -- make use of the special variable TG_OP to work out the operation.
            --
            IF (TG_OP = 'DELETE') THEN
                INSERT INTO neb_district_audit VALUES (DEFAULT, 'D', now(), user, OLD.*);
                RETURN OLD;
            ELSIF (TG_OP = 'UPDATE') THEN
                INSERT INTO neb_district_audit VALUES (DEFAULT, 'U', now(), user, NEW.*);
                RETURN NEW;
            ELSIF (TG_OP = 'INSERT') THEN
                INSERT INTO neb_district_audit VALUES (DEFAULT, 'I', now(), user, NEW.*);
                RETURN NEW;
            END IF;
            RETURN NULL; -- result is ignored since this is an AFTER trigger
        END;
    $usr_audit$ LANGUAGE plpgsql;
    
    CREATE TRIGGER neb_district_audit AFTER INSERT OR UPDATE OR DELETE ON neb_district 
        FOR EACH ROW EXECUTE PROCEDURE neb_district_audit();
    
    
    Create view district as select nda.* 
        from neb_district nd left join neb_district_audit nda on nd.id_neb_district = nda.id_neb_district;
        

--
-- NEB DISTRICT TABLE AND AUDIT TABLE
--
    drop table neb_school cascade;
    CREATE TABLE neb_school (
        id_author           integer DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,
    
        id_neb_school       Serial PRIMARY KEY,

        id_county           character(2) NOT NULL,
        id_district         character(4) NOT NULL,
        id_school           character(3) NOT NULL,
        name_school         character varying NOT NULL,
        status              character varying DEFAULT 'Inactive'::character varying,

        address_street1 character varying NOT NULL,
        address_street2 character varying,
        address_city character varying NOT NULL,
        address_state character(2) NOT NULL,
        address_zip character varying NOT NULL,
        id_school_mgr integer,
        id_account_sprv integer,
        phone_main character varying,
        minutes_per_week smallint,
        date_report1 date,
        date_report2 date,
        date_report3 date,
        date_report4 date,
        date_report5 date,
        date_report6 date,
        id_school_mgr_old integer,
        id_account_sprv_old integer        
    );
    
    
    drop table neb_school_audit cascade;
    CREATE TABLE neb_school_audit (
        id_neb_district_data        Serial PRIMARY KEY,
            operation 		        char(1)   NOT NULL,
            stamp            		timestamp NOT NULL,
            userid            		text      NOT NULL,
            

        id_author           integer DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,
    
        id_neb_school       integer,
    
        id_county           character(2) NOT NULL,
        id_district         character(4) NOT NULL,
        id_school           character(3) NOT NULL,
        name_school         character varying NOT NULL,
        status character varying DEFAULT 'Inactive'::character varying,
    
        address_street1 character varying NOT NULL,
        address_street2 character varying,
        address_city character varying NOT NULL,
        address_state character(2) NOT NULL,
        address_zip character varying NOT NULL,
        id_school_mgr integer,
        id_account_sprv integer,
        phone_main character varying,
        minutes_per_week smallint,
        date_report1 date,
        date_report2 date,
        date_report3 date,
        date_report4 date,
        date_report5 date,
        date_report6 date,
        id_school_mgr_old integer,
        id_account_sprv_old integer
    );
    
    
    CREATE OR REPLACE FUNCTION neb_school_audit() RETURNS TRIGGER AS $usr_audit$
        BEGIN
            --
            -- Create a row in neb_school_audit to reflect the operation performed on MinUser,
            -- make use of the special variable TG_OP to work out the operation.
            --
            IF (TG_OP = 'DELETE') THEN
                INSERT INTO neb_school_audit VALUES (DEFAULT, 'D', now(), user, OLD.*);
                RETURN OLD;
            ELSIF (TG_OP = 'UPDATE') THEN
                INSERT INTO neb_school_audit VALUES (DEFAULT, 'U', now(), user, NEW.*);
                RETURN NEW;
            ELSIF (TG_OP = 'INSERT') THEN
                INSERT INTO neb_school_audit VALUES (DEFAULT, 'I', now(), user, NEW.*);
                RETURN NEW;
            END IF;
            RETURN NULL; -- result is ignored since this is an AFTER trigger
        END;
    $usr_audit$ LANGUAGE plpgsql;
    
    CREATE TRIGGER neb_school_audit AFTER INSERT OR UPDATE OR DELETE ON neb_school 
        FOR EACH ROW EXECUTE PROCEDURE neb_school_audit();
    
    
    Create view school as select nsa.* 
        from neb_school ns left join neb_school_audit nsa on ns.id_neb_school = nsa.id_neb_school;
        








--insert into neb_county (id_county, name_county) VALUES ('99', 'jesse county');
--insert into neb_district (id_county, id_district, name_district) VALUES ('99', '9999', 'jesse district');
--insert into neb_school (id_county, id_district, id_school, name_school, address_street1, address_city, address_state, address_zip) VALUES ('99', '9999', '999', 'jesse school', '123 any street', 'my city', 'NE', '43232');
--select * from neb_county_audit;
--select * from neb_district_audit;
--select * from neb_school_audit;
