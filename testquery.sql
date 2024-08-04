SELECT
    t0.id AS id_1,
    t0.city AS city_2,
    t0.ip_byte_1_from AS ip_byte_1_from_3,
    t0.ip_byte_1_to AS ip_byte_1_to_4,
    t0.ip_byte_2_from AS ip_byte_2_from_5,
    t0.ip_byte_2_to AS ip_byte_2_to_6,
    t0.ip_byte_3_from AS ip_byte_3_from_7,
    t0.ip_byte_3_to AS ip_byte_3_to_8,
    t0.ip_byte_4_from AS ip_byte_4_from_9,
    t0.ip_byte_4_to AS ip_byte_4_to_10
FROM
    iprangelocation t0
WHERE
    (
        (
            (
                (
                    (
                        t0.ip_byte_1_from <= 200
                        AND t0.ip_byte_1_to >= 200
                    )
                    AND t0.ip_byte_2_from <= 50
                )
                AND t0.ip_byte_2_to >= 50
            )
            AND t0.ip_byte_3_from <= 42
        )
        AND t0.ip_byte_3_to >= 42
    );