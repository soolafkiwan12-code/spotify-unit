USE spotify_clone;

-- =========================
-- SELECT
-- =========================
SELECT id, name, email FROM users;

SELECT t.id, t.title, t.genre, t.duration_seconds
FROM tracks t
WHERE t.genre = 'Pop';

-- بحث LIKE (نفس فكرة صفحة Search في PHP)
SELECT t.id, t.title, a.title AS album_title, ar.name AS artist_name
FROM tracks t
JOIN albums a ON a.id = t.album_id
JOIN artists ar ON ar.id = a.artist_id
WHERE t.title LIKE '%light%'
   OR ar.name LIKE '%light%';

-- =========================
-- INSERT
-- =========================
INSERT INTO artists (name, country, bio)
VALUES ('Cairokee', 'Egypt', 'Egyptian rock band.');

INSERT INTO albums (artist_id, title, release_year)
VALUES (LAST_INSERT_ID(), 'The Ugly Ducklings', 2024);

-- =========================
-- UPDATE
-- =========================
UPDATE tracks
SET genre = 'Synth-pop', duration_seconds = 200
WHERE title = 'Blinding Lights';

UPDATE users
SET name = 'Lina'
WHERE email = 'user@spotify.test';

-- =========================
-- DELETE
-- =========================
DELETE FROM likes
WHERE user_id = 3 AND track_id = 5;

-- حذف فنان يحذف ألبوماته وأغانيه بسبب ON DELETE CASCADE
-- DELETE FROM artists WHERE name = 'Cairokee';

-- =========================
-- JOINS
-- =========================

-- INNER JOIN: أغنية + ألبوم + فنان
SELECT
  t.title AS track_title,
  a.title AS album_title,
  ar.name AS artist_name,
  t.genre
FROM tracks t
INNER JOIN albums a ON a.id = t.album_id
INNER JOIN artists ar ON ar.id = a.artist_id
ORDER BY ar.name, a.title;

-- LEFT JOIN: كل الفنانين حتى لو ما عندهم ألبوم
SELECT ar.name AS artist_name, a.title AS album_title
FROM artists ar
LEFT JOIN albums a ON a.artist_id = ar.id
ORDER BY ar.name;

-- JOIN لقوائم التشغيل (Many-to-Many)
SELECT
  u.name AS user_name,
  p.name AS playlist_name,
  t.title AS track_title,
  pt.position
FROM playlists p
INNER JOIN users u ON u.id = p.user_id
INNER JOIN playlist_track pt ON pt.playlist_id = p.id
INNER JOIN tracks t ON t.id = pt.track_id
WHERE p.id = 1
ORDER BY pt.position;

-- JOIN للإعجابات + عدّاد
SELECT t.title, COUNT(l.user_id) AS likes_count
FROM tracks t
LEFT JOIN likes l ON l.track_id = t.id
GROUP BY t.id, t.title
ORDER BY likes_count DESC;

-- JOIN المستخدم مع الدور (صلاحيات)
SELECT u.name, u.email, r.name AS role_name
FROM users u
INNER JOIN roles r ON r.id = u.role_id;
