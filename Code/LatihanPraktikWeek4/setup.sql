CREATE DATABASE IF NOT EXISTS Kampus;
USE Kampus;

CREATE TABLE IF NOT EXISTS matakuliah (
    Kode_MK VARCHAR(10) PRIMARY KEY,
    Nama_MK VARCHAR(100) NOT NULL,
    SKS INT NOT NULL,
    Semester INT NOT NULL
);

INSERT INTO matakuliah (Kode_MK, Nama_MK, SKS, Semester) VALUES
('1802012', 'Akuisisi Data', 2, 2),
('1802013', 'Pemrograman Web', 3, 2),
('1803012', 'Mikrokontroler', 2, 3),
('1804013', 'Pemrograman Berorientasi Objek', 3, 4)
ON DUPLICATE KEY UPDATE Nama_MK=VALUES(Nama_MK), SKS=VALUES(SKS), Semester=VALUES(Semester);
