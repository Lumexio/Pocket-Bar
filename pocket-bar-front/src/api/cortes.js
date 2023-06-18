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
        const workshift_report = response.data.workshift_report;

        resolve({
          data, workshift_report
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postNominas(nominas) {

  return new Promise((resolve, reject) => {
    axios
      .post("api/nominas/pay", nominas)
      .then(response => {


        resolve({
          response
        });
      })
      .catch((error) => { reject(error); });
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
export function postBoxOpen(pack) { 
  console.log(pack);
  return new Promise((resolve, reject) => {
    axios
      .post("api/workshift/start", pack)
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
export function putBoxClose(pack) { 
  console.log(pack);
  return new Promise((resolve, reject) => {
    axios
      .put("api/workshift/close", pack)
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
          setTimeout(function () {
            store.commit("setdanger", null);
          }, 2000);
        }
      });
  });
}




export default { getCotizado, postCerrarticket, postCancelticket, postNominas, postBoxOpen,putBoxClose }