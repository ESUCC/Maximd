-- \i '/var/www/nebraska_survey/data/jesse_tests/pg_setup_user.sql'
--
-- NEB USER
--
    drop table neb_user cascade;
    CREATE TABLE neb_user (
        id_author           integer DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,

        id_neb_user		        Serial PRIMARY KEY,

        address_street1         character varying,
        address_street2         character varying,
        address_city		    character varying,
        address_state		    character(2),
        address_zip		        character varying,
        "class"                 smallint NOT NULL,
        email_address		    character varying,
        id_county		        character(2),
        id_district		        character(4),
        id_school		        character(3),
        name_first		        character varying NOT NULL,
        name_last		        character varying NOT NULL,
        name_middle		        character varying,
        phone_home		        character varying,
        phone_work		        character varying,
        access_list		        character varying,
        status		            character varying DEFAULT 'Inactive'::character varying,
        user_name		        character varying,
        "password"              character varying,
        password_reset_flag		boolean,
        date_last_pw_change		timestamp with time zone DEFAULT "timestamp"('now'::text),
        date_expiration		    date,
        online_access		    character varying DEFAULT 'Disabled'::character varying,
        last_login		        timestamp with time zone,
        email_valid		        boolean,
        id_personnel_master		integer,
        single_converted		boolean,
        scrubflag		        character varying,
        survey_date_list		character varying,
        email_bu		        character varying,
        team_count		        integer,
        case_mgr_count		    integer,
        update_flag		        integer,
        vere_ss_update		    boolean
    );
    
    drop table neb_user_audit cascade;
    CREATE TABLE neb_user_audit (
        id_neb_county_data   	    Serial PRIMARY KEY,
            operation 		        char(1)   NOT NULL,
            stamp            		timestamp NOT NULL,
            userid            		text      NOT NULL,
            
        id_author           integer DEFAULT 0 NOT NULL,
        id_author_last_mod  integer DEFAULT 0 NOT NULL,
        timestamp_created   timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        timestamp_last_mod  timestamp with time zone DEFAULT "timestamp"('now'::text) NOT NULL,
        checkout_id_user    integer,
        checkout_time       integer,

        id_neb_user		        integer,

        address_street1         character varying,
        address_street2         character varying,
        address_city		    character varying,
        address_state		    character(2),
        address_zip		        character varying,
        "class"                 smallint NOT NULL,
        email_address		    character varying,
        id_county		        character(2),
        id_district		        character(4),
        id_school		        character(3),
        name_first		        character varying NOT NULL,
        name_last		        character varying NOT NULL,
        name_middle		        character varying,
        phone_home		        character varying,
        phone_work		        character varying,
        access_list		        character varying,
        status		            character varying DEFAULT 'Inactive'::character varying,
        user_name		        character varying,
        "password"              character varying,
        password_reset_flag		boolean,
        date_last_pw_change		timestamp with time zone DEFAULT "timestamp"('now'::text),
        date_expiration		    date,
        online_access		    character varying DEFAULT 'Disabled'::character varying,
        last_login		        timestamp with time zone,
        email_valid		        boolean,
        id_personnel_master		integer,
        single_converted		boolean,
        scrubflag		        character varying,
        survey_date_list		character varying,
        email_bu		        character varying,
        team_count		        integer,
        case_mgr_count		    integer,
        update_flag		        integer,
        vere_ss_update		    boolean
    );
    
    
    CREATE OR REPLACE FUNCTION neb_user_audit() RETURNS TRIGGER AS $usr_audit$
        BEGIN
            --
            -- Create a row in neb_user_audit to reflect the operation performed on MinUser,
            -- make use of the special variable TG_OP to work out the operation.
            --
            IF (TG_OP = 'DELETE') THEN
                INSERT INTO neb_user_audit VALUES (DEFAULT, 'D', now(), user, OLD.*);
                RETURN OLD;
            ELSIF (TG_OP = 'UPDATE') THEN
                INSERT INTO neb_user_audit VALUES (DEFAULT, 'U', now(), user, NEW.*);
                RETURN NEW;
            ELSIF (TG_OP = 'INSERT') THEN
                INSERT INTO neb_user_audit VALUES (DEFAULT, 'I', now(), user, NEW.*);
                RETURN NEW;
            END IF;
            RETURN NULL; -- result is ignored since this is an AFTER trigger
        END;
    $usr_audit$ LANGUAGE plpgsql;
    
    CREATE TRIGGER neb_user_audit AFTER INSERT OR UPDATE OR DELETE ON neb_user 
        FOR EACH ROW EXECUTE PROCEDURE neb_user_audit();
    
    
    Create view "user" as select nua.* 
        from neb_user nu left join neb_user_audit nua on nu.id_neb_user = nua.id_neb_user;
        




insert into neb_user (class, name_first, name_last) VALUES (1, 'jesse', 'lavere');