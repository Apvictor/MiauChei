import { ApiService } from './api.service';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class UserService {

  constructor(
    private api: ApiService,
  ) { }

  profile() {
    return new Promise((resolve, reject) => {
      this.api.get('/profile').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }

  postProfile(data) {
    return new Promise((resolve, reject) => {
      this.api.post('/profile', data).subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }
}
