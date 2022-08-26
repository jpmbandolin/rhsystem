import {Injectable} from '@angular/core';
import {HttpClient} from "@angular/common/http";
import {QueryParams} from "./_types/enums/query-params";
import {firstValueFrom} from "rxjs";
import {environment} from "../../../environments/environment";

@Injectable({
  providedIn: 'root'
})
export class APIService {

  constructor(private readonly http: HttpClient) {
  }

  public get(endpoint: string, params?: QueryParams) {
    return this.request(endpoint, 'get', params);
  }

  public post(endpoint: string, params?: any) {
    return this.request(endpoint, 'post', params);
  }

  public put(endpoint: string, body?: any) {
    return this.request(endpoint, 'put', body);
  }

  public delete(endpoint: string) {
    return this.request(endpoint, 'delete');
  }

  private request(endpoint: string, method: 'get' | 'post' | 'put' | 'delete', data?: any) {
    return firstValueFrom(
      this.http.request(method, environment.apiAddr + endpoint, {
        params: method === 'get' ? data : undefined,
        body: ['post', 'put'].includes(method) ? data : undefined,
        headers: {
          'Authorization': `Bearer ${localStorage.getItem('token')}`,
          'Content-Type': 'application/json; charset=utf-8'
        },
        observe: 'response'
      })
    );
  }

}
