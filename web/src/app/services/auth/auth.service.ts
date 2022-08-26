import {Injectable} from '@angular/core';
import {APIService} from "../api/api.service";

@Injectable({
  providedIn: 'root'
})
export class AuthService {

  constructor(private readonly api: APIService) {
  }

  public logIn() {
    this.api.post('user/login', {
      login: 'admin@rhsystem.com',
      password: '12'
    })
      .then((response) => {
        const {token} = response.body as { token: string };

        localStorage.setItem('token', token);
      });
  }
}
