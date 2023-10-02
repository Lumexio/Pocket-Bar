import axios from "axios";
import store from "@/store";
import router from "@/router";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";
export function putUsers(id, packet) {
  return new Promise((resolve, reject) => {
    axios
      .put("api/user/" + id, packet)
      .then((response) => {

        if (response.status === 200) {
          store.commit("setsuccess", true);
          setTimeout(function () {
            store.commit("setsuccess", false);
          }, 2000);
        }
        const resp = response.data;
        resolve({
          resp
        });
      })
      .catch((error) => {
        reject(error.response);
        if (error.response.status != 400 && error.response.status != 200) {
          store.commit("setdanger", true);
          setTimeout(function () {
            store.commit("setdanger", false);
          }, 2000);
        }
      }
      );
  })
}
export function postUsers(packet) {
  return new Promise((resolve, reject) => {
    axios
      .post("api/user", packet)
      .then((response) => {
        if (response.statusText === "Created") {
          store.commit("setsuccess", true);
        }
        const resp = response.data;
        resolve({
          resp
        });
      })
      .catch((error) => {
        reject(error.response);
        if (error.response.status != 400 && error.response.status != 200) {
          store.commit("setdanger", true);
          setTimeout(function () {
            store.commit("setdanger", null);
          }, 2000);
        }
      });
  })
}
export function getUsuarios(usersArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/user")
      .then((response) => {
        let user = response.data.users;
        let stats = response.status;
        user.forEach((element) => {
          let datos = {
            id: element.id,
            name: element.name,
            name_rol: element.name_rol,
            nominas: element.nominas,
            active: ((element.active === 1) ? true : false),
          };
          if (!datos) return;
          usersArray.push(datos);
        });
        resolve({
          usersArray, stats
        });
      })
      .catch((error) => {
        reject(error.response);
        if (error.response.status != 400 && error.response.status != 200) {
          store.commit("setdanger", true);
          setTimeout(function () {
            store.commit("setdanger", null);
          }, 2000);
        }
      });
  })
}
export function LogIn(packet) {
  return new Promise((resolve, reject) => {
    axios
      .get("sanctum/csrf-cookie")
      .then((response) => {
        response;
        axios
          .post("api/login", packet)
          .then((response) => {

            let rol = response.data.user.rol_id;
            let userId = response.data.user.id;
            store.commit("setrol", rol);
            store.commit("setUserId", userId);
            let validado = response.request.withCredentials;

            if (validado == true) {
              store.commit("SET_TOKEN", response.data.token);
              let token = store.state.token;
              store.dispatch("login", { token });
              switch (rol) {
                case 1:
                  router.push("/usuarios").catch(() => { });
                  break;
                case 2:
                  router.push("/articulos").catch(() => { });
                  break;
                case 3:
                  router.push("/ordenes").catch(() => { });
                  break;
                case 4:
                  router.push("/mesero").catch(() => { });
                  break;
                case 5:
                  router.push("/barra").catch(() => { });
                  break;
                default:
                  alert("Cuanta no existe o es incorrecta");
                  break;
              }
            } else if (validado == false) {
              alert("Cuanta no existe o es incorrecta");
            }
            resolve({
              response
            });
          })
          .catch((error) => {
            reject(error.response);
            if (error.response.status != 400 && error.response.status != 200) {
              store.commit("setdanger", true);
              setTimeout(function () {
                store.commit("setdanger", null);
              }, 2000);
            }
          });
      })
      .catch((e) => {
        reject(e.response);
        if (e.response.status != 400 && e.response.status != 200) {
          store.commit("setdanger", true);
          setTimeout(function () {
            store.commit("setdanger", null);
          }, 2000);
        }
      });
  })
}
export function Logout() {
  return new Promise((resolve, reject) => {
    axios
      .get("api/logout")
      .then((response) => {
        const data = response.data;
        const status = response.status;
        resolve({
          data, status
        });
      })
      .catch((error) => {
        reject(error.response);
        if (error.response.status != 400 && error.response.status != 200) {
          store.commit("setdanger", true);
          setTimeout(function () {
            store.commit("setdanger", null);
          }, 2000);
        }
      });
  })
}
export default { postUsers, getUsuarios, putUsers, Logout, LogIn };