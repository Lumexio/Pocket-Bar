import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getTickets(ticketsArray) {

  return new Promise((resolve, reject) => {
    axios
      .get("api/tickets/list")
      .then(response => {
        const tickets = response.data.data;
        const stats = response.status;
        tickets.forEach((element) => {
          let datos = {
            id: element.id,
            user_name: element.user_name,
            client_name: element.client_name,
            ticket_date: element.ticket_date,
            total: element.total,
            status: element.status,
            cancel_confirm: element.cancel_confirm,

          };
          if (!datos) return;
          ticketsArray.push(datos);
        });

        resolve({
          stats, ticketsArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postTickets(enviar) {


  axios
    .post("api/tickets/create", enviar)
    .then((response) => {

      if (response.status == 200) {
        store.commit("setsuccess", true);
        store.commit("setstatcode", 200);
        setTimeout(function () {
          store.commit("setsuccess", null);
        }, 2000);

      }

    })
    .catch((e) => {
      console.log(e.message);
      if (e) {
        store.commit("setdanger", true);
      }
    });

}


export function getTicketsPWA(ticketsPWAArray, status) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/tickets/pwa/list", {
        params: {
          status: status
        }
      })
      .then(response => {

        const tickets = response.data.data;
        const stats = response.status;

        tickets.forEach((element) => {
          let datos = {
            id: element.id,
            fecha: element.fecha,
            titular: element.titular,
            total: element.total,
            productos: element.productos,
            status: element.status,
            mesa: element.mesa,
          };
          if (!datos) return;
          ticketsPWAArray.push(datos);
        });


        resolve({
          stats, ticketsPWAArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function getTicketsNotiPWA(ticketsPWANotiArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/ordenes/notificacion/productos")
      .then(response => {
        const tickets = response.data;
        tickets.forEach((element) => {
          let datos = {
            nombre_articulo: element.articulo.nombre_articulo,
            id: element.id,
            units: element.units,
            status: element.status,
          };
          if (element.mesero) {
            datos.nombre_mesero = element.mesero.name;
          }

          ticketsPWANotiArray.push(datos);
        });
        resolve({
          ticketsPWANotiArray
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postTicketsNotiPWA(enviar) {
  console.log("Data para terminar prod:", enviar);
  return new Promise((resolve, reject) => {
    axios
      .put("api/ordenes/notificacion/productos", enviar)
      .then((response) => {
        if (response.status == 200) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
          }, 2000);
        }
        resolve({
          response
        });
      })
      .catch((e) => {
        reject(e);
        if (e) {
          store.commit("setdanger", true);
        }
      });
  });

}



export default { getTickets, postTickets, postTicketsNotiPWA, getTicketsPWA, getTicketsNotiPWA }