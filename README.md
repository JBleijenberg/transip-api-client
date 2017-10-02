# Commands

## domain:info
Get information about the given domain

```$ transip.phar <domain> <type>```

### Arguments
```domain``` The domain you would like to see the records of. Using subdomains, will show the record info of that subdomain

```type``` Shows all records of this type (A, AAAA, CNAME, TEXT etc)

Example result:
```bash
$ transip.phar domain:info example.com A
```
```bash
Domain: example.com
Nameservers:
         - ns0.transip.nl
         - ns1.transip.net
         - ns2.transip.eu
DNS Entries:
+------+------+-----------------+--------+
| Name | Type | Content         | Expire |
+------+------+-----------------+--------+
| @    | A    | 123.123.123.123 | 86400  |
+------+------+-----------------+--------+
```