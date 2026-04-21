import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
import { getFirestore } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

const firebaseConfig = {
  apiKey: "AIzaSyCJ9WRXmzJNHEYCY6b8YIxqioYVtkLYqrE",
  authDomain: "fujeira-retro-store.firebaseapp.com",
  projectId: "fujeira-retro-store",
  storageBucket: "fujeira-retro-store.firebasestorage.app",
  messagingSenderId: "746375299950",
  appId: "1:746375299950:web:8935bf926a647b76e4377f"
};

const app = initializeApp(firebaseConfig);

export const db = getFirestore(app);
