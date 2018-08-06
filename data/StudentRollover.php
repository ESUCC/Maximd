Hi Jesse,

Its time to increase all the k thru 12 grades by one increment.    I am replying to the email that I sent you last year that has some computer code that you had wrote.  Hopefully that will help.

In addition to the usual districts that we exclude from the upload:
40-0002
47-0001
40-0082
61-0004
61-0049
47-0100
77-0027
55-0001

I would also like to add Chadron Public Schools.  They had jumped the gun and manually added a grade level for everyone.  Their district ID# is:   23-0002




===================-- 2008 --===================

--GRADE ROLLOVER
-- UPDATE NUMERIC GRADES
update iep_student set 
    grade = grade::text::integer + 1 
where grade in 
( '01', '02', '03', '04', '05', '06', '07', '08', '09', '10', 
'11', '1', '2', '3', '4', '5', '6', '7', '8', '9') and

not (id_county = '40' and id_district = '0002') and 
not (id_county = '47' and id_district = '0001') and 
not (id_county = '40' and id_district = '0082') and 
not (id_county = '61' and id_district = '0004') and 
not (id_county = '61' and id_district = '0049') and 
not (id_county = '47' and id_district = '0100') and 
not (id_county = '77' and id_district = '0027') and 
not (id_county = '23' and id_district = '0002') and 
not (id_county = '40' and id_district = '0083') and 
not (id_county = '55' and id_district = '0001');




-- UPDATE KINDERGARTEN
update iep_student set 
    grade = 1 
where grade = 'K' and

not (id_county = '40' and id_district = '0002') and 
not (id_county = '47' and id_district = '0001') and 
not (id_county = '40' and id_district = '0082') and 
not (id_county = '61' and id_district = '0004') and 
not (id_county = '61' and id_district = '0049') and 
not (id_county = '47' and id_district = '0100') and 
not (id_county = '77' and id_district = '0027') and 
not (id_county = '23' and id_district = '0002') and 
not (id_county = '40' and id_district = '0083') and 
not (id_county = '55' and id_district = '0001');


== Notes ==
Grand Island- 40-0002
Howard County  47-0001
Hall82 40-0082
Hall83 40-0083  
Merrit County  61-0004
Merrit County  61-0049
Chadron Public Schools 23-0002
