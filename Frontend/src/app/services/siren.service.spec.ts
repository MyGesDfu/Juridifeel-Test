import { TestBed } from '@angular/core/testing';

import { SirenService } from './siren.service';

describe('SirenService', () => {
  let service: SirenService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(SirenService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
