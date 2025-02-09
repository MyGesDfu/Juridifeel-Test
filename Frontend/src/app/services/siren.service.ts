import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SirenService {

  private apiUrl = 'http://localhost:9001/api/etablissement'; // Remplace cette URL par celle de ton API Symfony

  constructor(private http: HttpClient) {}

  getEtablissement(siren: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${siren}`);
  }
}
