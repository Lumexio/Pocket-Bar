import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getCotizado() {

  return new Promise((resolve, reject) => {
    axios
      .get("api/caja/mustbe")
      .then(response => {
        let data = {};
        response.data.must_be.forEach(item => {
          data.total_night = item.total_night;
          data.type = item.type;
          data.details = item.details;

        });

        resolve({
          data
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postNominas(nominas) {

  return new Promise((resolve, reject) => {
    axios
      .post("api/nominas/pay", nominas)
      .then(response => {
        console.log(response);

        resolve({
          response
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postCerrarticket(pack) {

  return new Promise((resolve, reject) => {
    axios
      .post("api/tickets/pay", pack)
      .then(response => {
        if (response.status === 200) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
            store.commit("setstatcode", null);
          }, 2000);
        }
        resolve({
          response
        });
      })
      .catch((error) => {
        reject(error);
        if (error) {
          store.commit("setdanger", true);
        }
      });
  });
}
export function postCancelticket(pack) {
  return new Promise((resolve, reject) => {
    axios
      .post("api/tickets/cancel", pack)
      .then(response => {
        console.log("APi resp", response);
        if (response.status === 200) {
          store.commit("setsuccess", true);
          store.commit("setstatcode", 200);
          setTimeout(function () {
            store.commit("setsuccess", null);
            store.commit("setstatcode", null);
          }, 2000);
        }
        resolve({
          response
        });
      })
      .catch((error) => {
        reject(error);
        if (error) {
          store.commit("setdanger", true);
        }
      });
  });
}




export default { getCotizado, postCerrarticket, postCancelticket, postNominas }