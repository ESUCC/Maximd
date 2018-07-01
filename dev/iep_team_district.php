drop table iep_team_district ;
CREATE TABLE iep_team_district (
    timestamp_created timestamp without time zone,
    timestamp_last_mod timestamp without time zone,
    id_iep_team_district serial NOT NULL,
    id_form_004 integer,
    participant_name character varying,
    relationship_desc character varying,
    relationship_district character varying,
    sortnum integer,
    date_signed date,
    relationship_other character varying
) with oids;


INSERT INTO iep_team_district VALUES (NULL, NULL, nextval('iep_team_district_id_iep_team_district_seq'::regclass), 1255692, 'Jason Fish', 'Assistive Technology', NULL, 1, '2009-06-01', 'tester');
INSERT INTO iep_team_district VALUES (NULL, NULL, nextval('iep_team_district_id_iep_team_district_seq'::regclass), 1255692, 'Mike Peters', 'Counselor', NULL, 2, '2009-06-17', 'test');
