import { TestBed } from '@angular/core/testing';

import { NafService } from './naf.service';

describe('NafService', () => {
  let service: NafService;

  beforeEach(() => {
    TestBed.configureTestingModule({});
    service = TestBed.inject(NafService);
  });

  it('should be created', () => {
    expect(service).toBeTruthy();
  });
});
