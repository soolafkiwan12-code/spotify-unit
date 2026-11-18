/**
 * Spotify Clone — Vanilla JavaScript
 * البحث، القائمة، المشغّل، والتحقق من النماذج
 */
(function () {
  "use strict";

  const catalog = [
    { title: "Garee2a Awy — A COLORS SHOW", artist: "TUL8TE", type: "album" },
    { title: "WILDFLOWER", artist: "Billie Eilish", type: "album" },
    { title: "Born To Die — The Paradise Edition", artist: "Lana Del Rey", type: "album" },
    { title: "Hit Me Hard and Soft", artist: "Billie Eilish", type: "album" },
    { title: "Amr Diab", artist: "Amr Diab", type: "artist" },
    { title: "Billie Eilish", artist: "Billie Eilish", type: "artist" },
    { title: "The Weeknd", artist: "The Weeknd", type: "artist" },
    { title: "Lana Del Rey", artist: "Lana Del Rey", type: "artist" },
    { title: "Jadal", artist: "Jadal", type: "artist" },
  ];

  const state = {
    playing: false,
    title: "Blinding Lights",
    artist: "The Weeknd",
    progress: 32,
    timer: null,
  };

  document.addEventListener("DOMContentLoaded", init);

  function init() {
    setupMobileMenu();
    setupSearch();
    setupPlayer();
    setupMediaClicks();
    setupForms();
    setupHeaderScroll();
    setupActiveNav();
  }

  function setupMobileMenu() {
    const toggle = document.getElementById("menu-toggle");
    const nav = document.getElementById("main-nav");
    if (!toggle || !nav) return;

    toggle.addEventListener("click", function () {
      const open = nav.classList.toggle("is-open");
      toggle.setAttribute("aria-expanded", open ? "true" : "false");
    });

    nav.querySelectorAll("a").forEach(function (link) {
      link.addEventListener("click", function () {
        nav.classList.remove("is-open");
        toggle.setAttribute("aria-expanded", "false");
      });
    });
  }

  function setupSearch() {
    const input = document.getElementById("site-search");
    const hint = document.getElementById("search-hint");
    if (!input) return;

    input.addEventListener("input", function () {
      const q = input.value.trim().toLowerCase();
      filterCards(".media-card", q);
      filterCards(".artist-card", q);

      if (hint) {
        if (!q) {
          hint.hidden = true;
          hint.textContent = "";
          return;
        }
        const matches = catalog.filter(function (item) {
          return (
            item.title.toLowerCase().includes(q) ||
            item.artist.toLowerCase().includes(q)
          );
        });
        hint.hidden = false;
        hint.textContent =
          matches.length === 0
            ? "No results for “" + input.value + "”"
            : matches.length + " result(s) in albums & artists";
      }
    });
  }

  function filterCards(selector, q) {
    document.querySelectorAll(selector).forEach(function (card) {
      const hay =
        (card.getAttribute("data-title") || "") +
        " " +
        (card.getAttribute("data-artist") || "") +
        " " +
        (card.textContent || "");
      const show = !q || hay.toLowerCase().includes(q);
      card.classList.toggle("is-hidden", !show);
    });
  }

  function setupPlayer() {
    const playBtn = document.getElementById("play-btn");
    const bar = document.getElementById("progress-bar");
    if (!playBtn) return;

    playBtn.addEventListener("click", function () {
      state.playing = !state.playing;
      playBtn.setAttribute("aria-pressed", state.playing ? "true" : "false");
      playBtn.textContent = state.playing ? "Pause" : "Play";
      if (state.playing) startProgress();
      else stopProgress();
    });

    if (bar) {
      bar.addEventListener("click", function (event) {
        const rect = bar.getBoundingClientRect();
        const ratio = (event.clientX - rect.left) / rect.width;
        state.progress = Math.max(0, Math.min(100, ratio * 100));
        renderProgress();
      });
    }

    renderNowPlaying();
    renderProgress();
  }

  function startProgress() {
    stopProgress();
    state.timer = setInterval(function () {
      state.progress += 0.4;
      if (state.progress >= 100) {
        state.progress = 0;
        state.playing = false;
        stopProgress();
        const playBtn = document.getElementById("play-btn");
        if (playBtn) {
          playBtn.textContent = "Play";
          playBtn.setAttribute("aria-pressed", "false");
        }
      }
      renderProgress();
    }, 200);
  }

  function stopProgress() {
    if (state.timer) {
      clearInterval(state.timer);
      state.timer = null;
    }
  }

  function renderProgress() {
    const fill = document.getElementById("progress-fill");
    if (fill) fill.style.width = state.progress + "%";
  }

  function renderNowPlaying() {
    const titleEl = document.getElementById("now-title");
    const artistEl = document.getElementById("now-artist");
    if (titleEl) titleEl.textContent = state.title;
    if (artistEl) artistEl.textContent = state.artist;
  }

  function setupMediaClicks() {
    document.querySelectorAll(".media-card, .artist-card, .feature-panel").forEach(function (card) {
      card.addEventListener("click", function (event) {
        if (event.target.closest("a")) return;
        const title = card.getAttribute("data-title");
        const artist = card.getAttribute("data-artist");
        if (!title) return;
        state.title = title;
        state.artist = artist || "Spotify";
        state.progress = 0;
        state.playing = true;
        renderNowPlaying();
        renderProgress();
        const playBtn = document.getElementById("play-btn");
        if (playBtn) {
          playBtn.textContent = "Pause";
          playBtn.setAttribute("aria-pressed", "true");
        }
        startProgress();
        showToast("Now playing: " + title);
      });
    });
  }

  function setupForms() {
    const signup = document.getElementById("signup-form");
    const contact = document.getElementById("contact-form");

    if (signup) {
      signup.addEventListener("submit", function (event) {
        event.preventDefault();
        const name = signup.querySelector('[name="name"]').value.trim();
        const email = signup.querySelector('[name="email"]').value.trim();
        const password = signup.querySelector('[name="password"]').value;
        const error = validateSignup(name, email, password);
        if (error) {
          showToast(error, true);
          return;
        }
        signup.reset();
        const thanks = document.getElementById("signup-done");
        if (thanks) thanks.classList.add("is-visible");
        showToast("Welcome " + name + "! Account created.");
      });
    }

    if (contact) {
      contact.addEventListener("submit", function (event) {
        event.preventDefault();
        const name = contact.querySelector('[name="name"]').value.trim();
        const email = contact.querySelector('[name="email"]').value.trim();
        const message = contact.querySelector('[name="message"]').value.trim();
        if (name.length < 2) return showToast("Please enter your full name.", true);
        if (!isEmail(email)) return showToast("Please enter a valid email.", true);
        if (message.length < 8) return showToast("Message must be at least 8 characters.", true);
        contact.reset();
        const thanks = document.getElementById("contact-done");
        if (thanks) thanks.classList.add("is-visible");
        showToast("Thanks " + name + ", your message was sent.");
      });
    }
  }

  function validateSignup(name, email, password) {
    if (name.length < 2) return "Display name must be at least 2 characters.";
    if (!isEmail(email)) return "Please enter a valid email.";
    if (password.length < 6) return "Password must be at least 6 characters.";
    if (!/[A-Za-z]/.test(password) || !/[0-9]/.test(password)) {
      return "Password should include letters and numbers.";
    }
    return "";
  }

  function isEmail(value) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value);
  }

  function setupHeaderScroll() {
    const header = document.querySelector(".site-header");
    if (!header) return;
    window.addEventListener("scroll", function () {
      header.classList.toggle("is-scrolled", window.scrollY > 24);
    });
  }

  function setupActiveNav() {
    const links = document.querySelectorAll(".nav a[href^='#']");
    const sections = [];
    links.forEach(function (link) {
      const id = link.getAttribute("href").slice(1);
      const section = document.getElementById(id);
      if (section) sections.push({ id: id, el: section, link: link });
    });
    if (!sections.length) return;

    window.addEventListener("scroll", function () {
      const y = window.scrollY + 120;
      let current = sections[0];
      sections.forEach(function (item) {
        if (item.el.offsetTop <= y) current = item;
      });
      links.forEach(function (link) {
        link.classList.toggle("is-active", link === current.link);
      });
    });
  }

  function showToast(message, isError) {
    let toast = document.getElementById("toast");
    if (!toast) {
      toast = document.createElement("div");
      toast.id = "toast";
      toast.className = "toast";
      document.body.appendChild(toast);
    }
    toast.textContent = message;
    toast.classList.toggle("toast--error", Boolean(isError));
    toast.classList.add("is-visible");
    clearTimeout(showToast._t);
    showToast._t = setTimeout(function () {
      toast.classList.remove("is-visible");
    }, 2800);
  }
})();
