const DEMO_USERS = [
  { email: "admin@spotify.test", password: "Admin@123", name: "Soola Admin", role: "admin" },
  { email: "editor@spotify.test", password: "Editor@123", name: "Music Editor", role: "editor" },
  { email: "user@spotify.test", password: "User@123", name: "Lina Listener", role: "listener" },
];

function currentUser() {
  try {
    return JSON.parse(localStorage.getItem("spotify_user") || "null");
  } catch (err) {
    return null;
  }
}

function canManage(user) {
  return Boolean(user && (user.role === "admin" || user.role === "editor"));
}

function loginUser(email, password) {
  const found = DEMO_USERS.find(function (item) {
    return item.email === email && item.password === password;
  });
  if (!found) return false;
  localStorage.setItem(
    "spotify_user",
    JSON.stringify({ name: found.name, email: found.email, role: found.role })
  );
  return true;
}

function logoutUser() {
  localStorage.removeItem("spotify_user");
}
