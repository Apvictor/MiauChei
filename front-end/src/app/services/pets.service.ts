import { ApiService } from './api.service';
import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class PetsService {

  constructor(
    private api: ApiService,
  ) { }

  recent() {
    return new Promise((resolve, reject) => {
      this.api.get('/recent').subscribe((res: any) => {
        console.log(res);
        resolve(res)
      }, err => {
        reject(err)
      })
    })
  }
}
