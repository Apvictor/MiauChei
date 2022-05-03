import { Injectable } from '@angular/core';
import { HttpClient, HttpHeaders } from '@angular/common/http';
import { environment } from 'src/environments/environment';

@Injectable({
  providedIn: 'root'
})
export class ApiService {

  private url = environment.api_hml

  token
  headers

  constructor(private http: HttpClient) {
    this.token = window.localStorage.getItem('access-token');
    this.headers = new HttpHeaders()
      .set('Accept', 'application/json')
      .set('Content-Type', 'application/json')
      .set('Authorization', 'Bearer ' + this.token)
  }

  post(serviceName, data) {
    const url = this.url + serviceName
    const options = { headers: this.headers, withCredentials: false }

    return this.http.post(url, data, options)
  }

  put(serviceName, data) {
    const url = this.url + serviceName
    const options = { headers: this.headers, withCredentials: false }

    return this.http.put(url, data, options)
  }

  get(serviceName) {
    const options = { headers: this.headers, withCredentials: false }
    const url = this.url + serviceName

    return this.http.get(url, options)
  }

}
