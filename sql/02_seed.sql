USE spotify_clone;

INSERT INTO roles (id, name, description) VALUES
  (1, 'admin', 'إدارة كاملة للموقع والمستخدمين'),
  (2, 'editor', 'إضافة وتعديل وحذف الأغاني والفنانين'),
  (3, 'listener', 'استماع وبحث بدون تعديل');

-- كلمات المرور (password_hash / bcrypt):
-- admin@spotify.test  → Admin@123
-- editor@spotify.test → Editor@123
-- user@spotify.test   → User@123
INSERT INTO users (role_id, name, email, password) VALUES
  (1, 'Soola Admin', 'admin@spotify.test', '$2y$10$1KwixHvD8lQa1eMe1Ieoie5lXwXnA/YwIaDnUKXJKRybjCgiZII12'),
  (2, 'Music Editor', 'editor@spotify.test', '$2y$10$MTcyJOnDmvC6OUbUYze7X.qO//i.ODAFmXcnpsg9Gpj6Fgv5y1TNe'),
  (3, 'Lina Listener', 'user@spotify.test', '$2y$10$ZTC1Avj91w2IFpPV1uXZUuea4pgsYyIoRSTvVZpxZYhym4PFkRZNK');

INSERT INTO artists (id, name, country, bio) VALUES
  (1, 'TUL8TE', 'Egypt', 'Egyptian artist featured on COLORS.'),
  (2, 'Billie Eilish', 'USA', 'Grammy-winning singer and songwriter.'),
  (3, 'Lana Del Rey', 'USA', 'Alternative pop and cinematic ballads.'),
  (4, 'The Weeknd', 'Canada', 'R&B and pop hits including Blinding Lights.'),
  (5, 'Amr Diab', 'Egypt', 'Legendary Arabic pop artist.'),
  (6, 'Jadal', 'Jordan', 'Jordanian rock band.');

INSERT INTO albums (id, artist_id, title, release_year, cover_image) VALUES
  (1, 1, 'Garee2a Awy — A COLORS SHOW', 2024, 'image/Screenshot 2026-07-22 144021.png'),
  (2, 2, 'WILDFLOWER', 2024, 'image/Screenshot 2026-07-22 144049.png'),
  (3, 3, 'Born To Die — The Paradise Edition', 2012, 'image/Screenshot 2026-07-22 144118.png'),
  (4, 2, 'Hit Me Hard and Soft', 2024, 'image/Screenshot 2026-07-22 155400.png'),
  (5, 4, 'After Hours', 2020, NULL),
  (6, 5, 'El Lilady', 2018, NULL),
  (7, 6, 'Malyeesh', 2016, NULL);

INSERT INTO tracks (id, album_id, title, duration_seconds, genre) VALUES
  (1, 1, 'Garee2a Awy', 214, 'Afrobeats'),
  (2, 2, 'WILDFLOWER', 261, 'Pop'),
  (3, 3, 'Born To Die', 285, 'Indie Pop'),
  (4, 3, 'Summertime Sadness', 265, 'Indie Pop'),
  (5, 4, 'BIRDS OF A FEATHER', 210, 'Pop'),
  (6, 5, 'Blinding Lights', 200, 'Synth-pop'),
  (7, 5, 'Save Your Tears', 215, 'Synth-pop'),
  (8, 6, 'El Lilady', 245, 'Arabic Pop'),
  (9, 7, 'Salma', 230, 'Rock');

INSERT INTO playlists (id, user_id, name, is_public) VALUES
  (1, 3, 'Night Drive', 1),
  (2, 1, 'Training Demo Mix', 1);

INSERT INTO playlist_track (playlist_id, track_id, position) VALUES
  (1, 6, 1),
  (1, 4, 2),
  (1, 2, 3),
  (2, 1, 1),
  (2, 9, 2),
  (2, 8, 3);

INSERT INTO likes (user_id, track_id) VALUES
  (3, 6),
  (3, 5),
  (2, 1),
  (1, 9);

INSERT INTO contact_messages (user_id, name, email, message) VALUES
  (NULL, 'Omar', 'omar@email.com', 'How do I create a playlist?');
