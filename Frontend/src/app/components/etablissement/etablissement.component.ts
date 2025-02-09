import { Component, OnInit } from '@angular/core';
import { SirenService } from '../../services/siren.service';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

@Component({
  selector: 'app-etablissement',
  standalone: true,
  imports: [CommonModule, FormsModule],  
  templateUrl: './etablissement.component.html',
  styleUrls: ['./etablissement.component.scss']
})
export class EtablissementComponent implements OnInit {
  siren: string = '';
  data: any = null;
  error: string = '';

  constructor(private sirenService: SirenService) {}

  ngOnInit(): void {}

  onSearch(): void {
    if (this.siren.trim().length === 9) { // Vérifie que le SIREN est valide
      this.sirenService.getEtablissement(this.siren).subscribe({
        next: (response) => {
          console.log('Données récupérées:', response); // DEBUG: Vérifier la structure des données
          this.data = response; // Assigner les données récupérées
          this.error = ''; 
        },
        error: (err) => {
          console.error('Erreur lors de la récupération des données:', err);
          this.error = 'Impossible de récupérer les données. Vérifiez le SIREN et réessayez.';
          this.data = null;
        }
      });
    } else {
      this.error = 'Le numéro de SIREN doit contenir 9 chiffres.';
      this.data = null;
    }
  }
}
