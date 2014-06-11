<?php 
/* 
 * Schema functions
 */

return array(

    "GetFamilyTree" =>
           " 
            CREATE DEFINER=`root`@`localhost` FUNCTION `GetFamilyTree`(GivenID INT) RETURNS text CHARSET latin1
                DETERMINISTIC
            BEGIN
                DECLARE rv,q,queue,queue_children TEXT;
                DECLARE queue_length,front_id,pos INT;
                SET rv = '';
                SET queue = GivenID;
                SET queue_length = 1;
                WHILE queue_length > 0 DO
                    SET front_id = FORMAT(queue,0);
                    IF queue_length = 1 THEN
                        SET queue = '';
                    ELSE
                        SET pos = LOCATE(',',queue) + 1;
                        SET q = SUBSTR(queue,pos);
                        SET queue = q;
                    END IF;
                    SET queue_length = queue_length - 1;
                    SELECT IFNULL(qc,'') INTO queue_children
                    FROM (SELECT GROUP_CONCAT(id_cat) qc
                    FROM es_cat WHERE parent_id = front_id) A;
                    IF LENGTH(queue_children) = 0 THEN
                        IF LENGTH(queue) = 0 THEN
                            SET queue_length = 0;
                        END IF;
                    ELSE
                        IF LENGTH(rv) = 0 THEN
                            SET rv = queue_children;
                        ELSE
                            SET rv = CONCAT(rv,',',queue_children);
                        END IF;
                        IF LENGTH(queue) = 0 THEN
                            SET queue = queue_children;
                        ELSE
                            SET queue = CONCAT(queue,',',queue_children);
                        END IF;
                        SET queue_length = LENGTH(queue) - LENGTH(REPLACE(queue,',','')) + 1;
                    END IF;
                END WHILE;
                RETURN rv;
            END ",
            
    "SPLIT_STRING" => 
            " 
            CREATE DEFINER=`root`@`localhost` FUNCTION `SPLIT_STRING`(str TEXT, delim TEXT, pos INT) RETURNS text CHARSET utf8
            RETURN REPLACE(SUBSTRING(SUBSTRING_INDEX(str, delim, pos),
                   LENGTH(SUBSTRING_INDEX(str, delim, pos-1)) + 1),
                   delim, '') ",
                   
);