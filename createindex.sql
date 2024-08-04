CREATE INDEX idx_ip_range ON iprangelocation (
    ip_byte_1_from,
    ip_byte_1_to,
    ip_byte_2_from,
    ip_byte_2_to,
    ip_byte_3_from,
    ip_byte_3_to,
    ip_byte_4_from,
    ip_byte_4_to
);