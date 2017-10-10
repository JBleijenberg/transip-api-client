# Commands

## domain:info
Get information about the given domain

```
Usage:
  domain:info <domain> [<type>]

Arguments:
  domain                The domain you would like to see the records of. Using subdomains, will show the record info of that subdomain
  type                  The recordtype you would like to see
  
```

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

## dns:add
Add a new DNS record to the given domain

```
Usage:
  dns:add [options] [--] <content>

Arguments:
  content               The content of the new DNS record

Options:
      --name=NAME       The name of the new record
      --domain=DOMAIN   The domains the new record should be added to
      --type=TYPE       The type of the new record. Must be of type A, AAAA, CNAME, MX, NS, SRV or TXT
      --ttl=TTL         The TTL of the new record. Values can be 50, 300, 3600 or 86400.
```      

## dns:edit
Edit the content of an existing DNS entry.

```
Usage:
  dns:edit [options] [--] <new-content>

Arguments:
  new-content              The new content for this record

Options:
      --domain=DOMAIN      The domains of the record that will be edited
      --type=TYPE          The curent type of the record. Must be of type A, AAAA, CNAME, MX, NS, SRV or TXT
      --name=NAME          The name of the record
      --content[=CONTENT]  The content of the record.
```

## dns:delete
Delete an existing DNS entry from the given domain.
When you don't supply any paremeters, you will be presented with a list so you can select (multiple) records to delete.

```
Usage:
  dns:delete [options] [--] [<name>]
  dns:remove

Arguments:
  name                     The name of the record

Options:
      --domain=DOMAIN      The domains the record should be deleted from
      --type[=TYPE]        The type of the record. Must be of type A, AAAA, CNAME, MX, NS, SRV or TXT
      --content[=CONTENT]  The content of the record.
```