import {Injectable} from '@angular/core';
import Swal from "sweetalert2";

@Injectable({
  providedIn: 'root'
})
export class SwalService {

  public success(text: string, title = 'Sucesso') {
    return Swal.fire({
      title,
      text,
      icon: 'success'
    });
  }

  public warning(text: string, title = 'Atenção') {
    return Swal.fire({
      title,
      text,
      icon: 'warning'
    });
  }

  public error(text: string, title = 'Erro') {
    return Swal.fire({
      title,
      text,
      icon: 'error'
    });
  }

  public info(text: string, title = 'Informação') {
    return Swal.fire({
      title,
      text,
      icon: 'info'
    });
  }

  public confirm(
    text: string,
    confirmButtonText = 'Sim',
    denyButtonText = 'Não',
    title = 'Atenção'
  ) {
    return Swal.fire({
      title,
      text,
      confirmButtonText,
      denyButtonText,
      showDenyButton: true,
      icon: 'question'
    })
  }
}
