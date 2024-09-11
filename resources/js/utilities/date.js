import {
  formatDistance,
  formatDistanceToNow,
  lightFormat,
  parseISO,
} from "date-fns";

const relativeDate = (date) =>
  formatDistance(parseISO(date), new Date(), { addSuffix: true });

const distanceToNow = (date) =>
  lightFormat(parseISO(date), "yyyy-MM-dd") +
  " (" +
  formatDistanceToNow(parseISO(date)) +
  ")";

export { relativeDate, distanceToNow };
