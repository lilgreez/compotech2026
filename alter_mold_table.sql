-- Drop unique keys on name and product_code
ALTER TABLE master_diesets DROP INDEX master_diesets_name_unique;
ALTER TABLE master_diesets DROP INDEX master_diesets_product_code_unique;

-- Add new columns if not exist
ALTER TABLE master_diesets
    ADD COLUMN kode_mold VARCHAR(100) NULL AFTER id,
    ADD COLUMN cavity VARCHAR(50) NULL AFTER name,
    MODIFY COLUMN status VARCHAR(50) NOT NULL DEFAULT 'Active',
    ADD COLUMN ownership VARCHAR(255) NULL AFTER status,
    ADD COLUMN type_cavity VARCHAR(50) NULL AFTER ownership,
    MODIFY COLUMN production_year VARCHAR(50) NULL,
    ADD COLUMN dimensi_mold VARCHAR(100) NULL AFTER production_year,
    ADD COLUMN diameter_lokating_ring VARCHAR(50) NULL AFTER dimensi_mold,
    ADD COLUMN type_cooling VARCHAR(50) NULL AFTER diameter_lokating_ring,
    ADD COLUMN type_eject VARCHAR(50) NULL AFTER type_cooling,
    ADD COLUMN type_gate VARCHAR(50) NULL AFTER type_eject,
    ADD COLUMN type_mold VARCHAR(50) NULL AFTER type_gate,
    ADD COLUMN ukuran_naple VARCHAR(50) NULL AFTER type_mold,
    ADD COLUMN qty_naple VARCHAR(50) NULL AFTER ukuran_naple,
    ADD COLUMN naple_std_long VARCHAR(50) NULL AFTER qty_naple,
    ADD COLUMN jumlah_cavity_std VARCHAR(50) NULL AFTER naple_std_long,
    ADD COLUMN jumlah_piston_slider VARCHAR(50) NULL AFTER jumlah_cavity_std,
    ADD COLUMN jumlah_gasvent VARCHAR(50) NULL AFTER jumlah_piston_slider,
    ADD COLUMN safety_mold VARCHAR(100) NULL AFTER jumlah_gasvent,
    ADD COLUMN qty_temp_channel_hr VARCHAR(100) NULL AFTER safety_mold;

CREATE INDEX idx_master_diesets_kode_mold ON master_diesets(kode_mold);
