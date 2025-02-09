import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class SiretService {

  private apiUrl = 'http://localhost:9001/api/siret';

  constructor(private http: HttpClient) {}

  getSiretDetails(siren: string): Observable<any> {
    return this.http.get<any>(`${this.apiUrl}/${siren}`);
  }
}
