import {Component} from '@angular/core';
import {APIService} from "./services/api/api.service";
import {SwalService} from "./services/swal/swal.service";
import {AuthService} from "./services/auth/auth.service";

@Component({
  selector: 'app-root',
  templateUrl: './app.component.html',
  styleUrls: ['./app.component.scss']
})
export class AppComponent {

  constructor(
    private readonly api: APIService,
    private readonly swal: SwalService,
    private readonly login: AuthService
  ) {
    login.logIn();

    console.log(localStorage.getItem('token'))
  }

}
