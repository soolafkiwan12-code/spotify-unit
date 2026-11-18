# Vue 3 Dashboard

لوحة تحكم Spotify باستخدام Vue 3 Composition API.

```bash
cd vue-dashboard
npm install
npm run dev
```

الحساب: `admin@spotify.test` / `Admin@123`

الوضع الافتراضي: Mock API حتى تعمل بدون PHP.

للربط مع Laravel Sanctum:

1. شغّل Laravel على `http://127.0.0.1:8000`
2. في `.env` ضع:

```
VITE_USE_MOCK=false
VITE_API_URL=http://127.0.0.1:8000/api
```

3. أعد تشغيل `npm run dev`
