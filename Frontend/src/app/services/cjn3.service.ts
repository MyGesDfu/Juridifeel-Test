import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class Cjn3Service {

  private apiUrl = 'http://localhost:9001/api/formejuridique';

  constructor(private http: HttpClient) {}

  getCJN3Category(code: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${code}`);
  }
}
