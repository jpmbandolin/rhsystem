import {HttpParams} from "@angular/common/http";

type Any = string | number | boolean | readonly (string | number | boolean)[];

export type QueryParams = HttpParams | {
  [p: string]: Any
};

