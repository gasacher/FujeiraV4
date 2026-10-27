import {
  collection,
  addDoc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

import { db } from "./firebase.js";

const SHEET_URL = "https://script.google.com/macros/s/AKfycbyxfB7t7HtmYFTfKjZ9lzFyR9ScuGDP5DHbDv8Mj2r0n_mMd8tA3u01aCp6R-EIjRUefA/exec";

export async function createOrder({
  orderId,
  nombre,
  apellido,
  telefono,
  email,
  direccion,
  cart,
  total,
  canal,
  metodoPago,
  estado
}) {
  const order = {
    orderId,
    canal,
    metodoPago,
    estado,
    cliente: { nombre, apellido, telefono, email, direccion },
    productos: cart,
    total,
    fecha: serverTimestamp()
  };

  const docRef = await addDoc(collection(db, "orders"), order);
  const firebaseId = docRef.id;

  saveOrderToSheet({
    orderId,
    firebaseId,
    nombre,
    apellido,
    telefono,
    email,
    direccion,
    cart,
    total,
    canal,
    metodoPago,
    estado,
    fecha: new Date().toISOString()
  }).catch(err => {
    console.warn("No se pudo replicar el pedido en Sheets:", err);
  });

  return firebaseId;
}

async function saveOrderToSheet(payload) {
  const body = new URLSearchParams({
    action: "addPedido",
    data: JSON.stringify(payload)
  });

  return fetch(SHEET_URL, {
    method: "POST",
    mode: "no-cors",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body
  });
}
