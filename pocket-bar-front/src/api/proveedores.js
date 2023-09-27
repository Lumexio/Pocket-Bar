import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://" + window.location.hostname/*"127.0.0.1"*/ + ":8000";


export function getProveedores(proveedorArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/provider/")
      .then(response => {
        const proveedor = response.data.proveedores;
        const stats = response.status;
        proveedor.forEach((element) => {
          let datos = {
            id: element.id,
            name: element.name,
            description: element.description,
            active: element.active,
          };
          if (!datos) return;
          proveedorArray.push(datos);
        });
        resolve({
          stats, proveedorArray
        });
      })
      .catch((error) => { reject(error); });
  });
}
export function postProveedores(enviar) {
  axios
    .post("api/provider/", enviar)
    .then((response) => {
      if (response.statusText === "Created") {
        store.commit("setsuccess", true);

      }
    })
    .catch((e) => {
      console.log(e.message);
      if (e) {
        store.commit("setdanger", true);
      }
    });
}
export function activationProveedores(id) {
  return new Promise((resolve, reject) => {
    axios.put("api/provider/activate/" + id).then((response) => {
      response;
      resolve(response);
    }).catch((error) => console.log(reject(error)));
  });
}
export function editProveedores(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getProveedores, postProveedores, activationProveedores, editProveedores };