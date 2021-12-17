import axios from "axios";
import store from "@/store";
axios.defaults.withCredentials = true;
axios.defaults.baseURL = "http://127.0.0.1:8000/";


export function getProveedores(proveedorArray) {
  return new Promise((resolve, reject) => {
    axios
      .get("api/proveedor")
      .then(response => {
        const proveedor = response.data;
        const stats = response.status;
        proveedor.forEach((element) => {
          let datos = {
            id: element.id,
            nombre_proveedor: element.nombre_proveedor,
            descripcion: element.descripcion,
          };
          if (!datos) return;
          proveedorArray.push(datos);
        });
        resolve({
          stats, proveedorArray
        });
      })
      .catch((error) => { console.log(error); reject(error); });
  });
}
export function postProveedores(enviar) {
  axios
    .post("api/proveedor", enviar)
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
export function deleteProveedores(id) {
  axios.delete("api/proveedor/" + id).catch((error) => console.log(error));
}
export function editProveedores(url) {
  axios
    .put(url)
    .then((response) => {
      response;
    })
    .catch((error) => console.log(error));
}

export default { getProveedores, postProveedores, deleteProveedores, editProveedores };