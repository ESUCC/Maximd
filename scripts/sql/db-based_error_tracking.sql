CREATE TYPE status as enum('pending','tracked');

CREATE TABLE db_error_log (
  error_log_id SERIAL,
  error_log_env varchar(40) NOT NULL,
  error_log_lvl integer DEFAULT NULL,
  error_log_msg_short varchar(80),
  error_log_username varchar(80),
  error_log_host varchar(120),
  error_log_browser varchar(120),
  error_log_status status NOT NULL DEFAULT 'pending',
  error_log_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  error_log_repetition_count integer NOT NULL,
  error_log_trace text,
  error_log_request text,
  error_log_session text,
  PRIMARY KEY (error_log_id));