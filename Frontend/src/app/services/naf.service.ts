import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class NafService {

  private apiUrl = 'http://localhost:9001/api/naf'; // Remplace cette URL par celle de ton API Symfony

  constructor(private http: HttpClient) {}

  getNafDetails(code: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${code}`);
  }
}
