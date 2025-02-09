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
    if (this.siren.trim().length === 9) {
      // Appeler le service pour récupérer les données de l'établissement
      this.sirenService.getEtablissement(this.siren).subscribe({
        next: (response) => {
          console.log('Données récupérées:', response); 
          this.data = response; // Enregistrer les données reçues
          this.error = ''; // Réinitialiser les erreurs
        },
        error: (err) => {
          console.error('Erreur lors de la récupération des données:', err);
          // En fonction de l'erreur retournée, afficher un message d'erreur
          if (err.status === 404) {
            this.error = 'Aucune donnée trouvée pour ce SIREN.';
          } else {
            this.error = 'Impossible de récupérer les données. Vérifiez le SIREN et réessayez.';
          }
          this.data = null; // Réinitialiser les données
        }
      });
    } else {
      this.error = 'Le numéro de SIREN doit contenir 9 chiffres.';
      this.data = null;
    }
  }
}
