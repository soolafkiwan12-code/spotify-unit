-- Spotify Clone — قاعدة البيانات
-- MySQL / MariaDB (XAMPP أو phpMyAdmin)
-- Primary Keys + Foreign Keys + علاقات

CREATE DATABASE IF NOT EXISTS spotify_clone
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE spotify_clone;

-- 1) الأدوار (صلاحيات بسيطة)
CREATE TABLE roles (
  id TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(50) NOT NULL UNIQUE,
  description VARCHAR(150) NULL
);

-- 2) المستخدمون  (N) ---- (1)  roles
CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  role_id TINYINT UNSIGNED NOT NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_users_role
    FOREIGN KEY (role_id) REFERENCES roles(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
);

-- 3) الفنانون
CREATE TABLE artists (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  country VARCHAR(80) NULL,
  bio TEXT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 4) الألبومات  (N) ---- (1)  artists
CREATE TABLE albums (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  artist_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  release_year YEAR NULL,
  cover_image VARCHAR(255) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_albums_artist
    FOREIGN KEY (artist_id) REFERENCES artists(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 5) الأغاني  (N) ---- (1)  albums
CREATE TABLE tracks (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  album_id INT UNSIGNED NOT NULL,
  title VARCHAR(150) NOT NULL,
  duration_seconds SMALLINT UNSIGNED NOT NULL DEFAULT 0,
  genre VARCHAR(60) NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_tracks_album
    FOREIGN KEY (album_id) REFERENCES albums(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 6) قوائم التشغيل  (N) ---- (1)  users
CREATE TABLE playlists (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NOT NULL,
  name VARCHAR(120) NOT NULL,
  is_public TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_playlists_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 7) أغاني القائمة  Many-to-Many: playlists N:M tracks
CREATE TABLE playlist_track (
  playlist_id INT UNSIGNED NOT NULL,
  track_id INT UNSIGNED NOT NULL,
  position INT UNSIGNED NOT NULL DEFAULT 1,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (playlist_id, track_id),
  CONSTRAINT fk_pt_playlist
    FOREIGN KEY (playlist_id) REFERENCES playlists(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_pt_track
    FOREIGN KEY (track_id) REFERENCES tracks(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 8) الإعجابات  Many-to-Many: users N:M tracks
CREATE TABLE likes (
  user_id INT UNSIGNED NOT NULL,
  track_id INT UNSIGNED NOT NULL,
  liked_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (user_id, track_id),
  CONSTRAINT fk_likes_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_likes_track
    FOREIGN KEY (track_id) REFERENCES tracks(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- 9) رسائل التواصل
CREATE TABLE contact_messages (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id INT UNSIGNED NULL,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(150) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT fk_contact_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON UPDATE CASCADE
    ON DELETE SET NULL
);
