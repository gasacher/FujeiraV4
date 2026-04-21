import {
  collection,
  addDoc,
  serverTimestamp
} from "https://www.gstatic.com/firebasejs/10.7.1/firebase-firestore.js";

import { db } from "./firebase.js"; 

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
  return docRef.id;
}
