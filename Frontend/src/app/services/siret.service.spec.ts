import { TestBed } from '@angular/core/testing';

import { SiretService } from './siret.service';

describe('SiretService', () => {
  let service: SiretService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(SiretService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
