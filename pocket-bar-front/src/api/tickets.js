import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getTickets(ticketsArray) {

  return new Promise((resolve, reject) => {
    axios
      .get("api/ticket/")
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
            details: element.details,

          };
          if (!datos) return;
          ticketsArray.push(datos);
        });


        resolve({
          stats, ticketsArray
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postTickets(enviar) {

  return new Promise((resolve, reject) => {
    axios
      .post("api/ticket/create", enviar)
      .then((response) => {
        const resp = response.data;
        const stats = response.status;
        if (response.status == 200) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
          }, 2000);

        }


        resolve({
          resp, stats
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

export function putTipUpdate(enviar) {

  return new Promise((resolve, reject) => {
    axios
      .put("api/ticket/tip", enviar)
      .then((response) => {
        const resp = response;

        if (response) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
          }, 2000);
        }
        resolve({
          resp
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


export function postAddProducts(enviar) {
  return new Promise((resolve, reject) => {
    axios
      .post("api/ticket/add/products", enviar)
      .then((response) => {
        if (response.status == 200) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
          }, 2000);
        }
        resolve(response);
      })
      .catch((e) => {
        console.error(e.message);
        if (e) {
          store.commit("setdanger", true);
        }
        reject(e);
      });
  });

}


export function getTicketsPWA(ticketsPWAArray, status) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/ticket/pwa", {
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
            tip: element.tip,
            specifictip: element.specifictip,
            productos: element.productos,
            status: element.status,
            nombre_mesa: element.nombre_mesa,
          };
          if (!datos) return;
          ticketsPWAArray.push(datos);
        });
        resolve({
          stats, ticketsPWAArray
        });
      })
      .catch((error) => { console.error(error); reject(error); });
  });
}
export function getTicketsNotiPWA(ticketsPWANotiArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/order/notificacion/productos")
      .then(response => {
        const tickets = response.data;
        tickets.forEach((element) => {
          let datos = {
            nombre_articulo: element.product.name,
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
  return new Promise((resolve, reject) => {
    axios
      .put("api/order/notificacion/productos", enviar)
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



export default { getTickets, postTickets, putTipUpdate, postAddProducts, postTicketsNotiPWA, getTicketsPWA, getTicketsNotiPWA }