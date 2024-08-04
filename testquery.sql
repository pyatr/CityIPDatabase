SELECT
    id,
    city,
    ip_address_from,
    ip_address_to
FROM
    iprangelocation
WHERE
    ip_address_from <= 1040792414
    AND ip_address_from >= 990460767
    AND ip_address_to >= 1040792414
LIMIT
    1;