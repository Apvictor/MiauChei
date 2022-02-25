import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private url = environment.api_url
  urlCep = environment.cep_url
  token = window.localStorage.getItem('CapacitorStorage.access-token');

  constructor(
    private http: HttpClient
  ) { }

  post(serviceName, data) {
    const headers = new HttpHeaders().set('Content-Type', 'application/json').set('Authorization', 'Bearer ' + this.token)
    const url = this.url + serviceName
    const options = { headers: headers, withCredentials: false }

    return this.http.post(url, data, options)
  }

  put(serviceName, data) {
    const headers = new HttpHeaders().set('Content-Type', 'application/json').set('Authorization', 'Bearer ' + this.token)
    const url = this.url + serviceName
    const options = { headers: headers, withCredentials: false }

    return this.http.put(url, data, options)
  }

  get(serviceName) {
    const headers = new HttpHeaders().set('Content-Type', 'application/json').set('Authorization', 'Bearer ' + this.token)
    const options = { headers: headers, withCredentials: false }
    const url = this.url + serviceName

    return this.http.get(url, options)
  }

}
