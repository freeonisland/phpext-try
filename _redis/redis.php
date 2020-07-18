```
$param = array('tcp://redis_stl:26379');
        $opt = [
            'replication' => 'sentinel',
            'service' => 'mymaster'
        ];
```

/*
    https://github.com/nrk/predis
    http://squizzle.me/php/predis/doc/#connection
    */

https://github.com/phpredis/phpredis


CLIENT 
    __construct"
  [1]=>
  string(10) "getProfile"
  [2]=>
  string(10) "getOptions"
  [3]=>
  string(12) "getClientFor"
  [4]=>
  string(7) "connect"
  [5]=>
  string(10) "disconnect"
  [6]=>
  string(4) "quit"
  [7]=>
  string(11) "isConnected"
  [8]=>
  string(13) "getConnection"
  [9]=>
  string(17) "getConnectionById"
  [10]=>
  string(10) "executeRaw"
  [11]=>
  string(6) "__call"
  [12]=>
  string(13) "createCommand"
  [13]=>
  string(14) "executeCommand"
  [14]=>
  string(8) "pipeline"
  [15]=>
  string(11) "transaction"
  [16]=>
  string(10) "pubSubLoop"
  [17]=>
  string(7) "monitor"
  [18]=>
  string(11) "getIterator"

  

    function connection()
    {
        //$client = new Predis\Client('unix:/path/to/redis.sock');
        $param = [
            'scheme' => 'tcp',
            'host' => 'redis',
            'port' => '6379'
        ];
        $opt = [

            'replication': true
        /*
            profile: specifies the profile to use to match a specific version of Redis.
            prefix: prefix string automatically applied to keys found in commands.
            exceptions: whether the client should throw or return responses upon Redis errors.
            connections: list of connection backends or a connection factory instance.
            cluster: specifies a cluster backend (predis, redis or callable object).
            replication: specifies a replication backend (TRUE, sentinel or callable object).
            aggregate: overrides cluster and replication to provide a custom connections aggregator.
            parameters:  list of default connection parameters for aggregate connections.

            $options = [
            'replication' => 'sentinel',
            'service' => 'mymaster',
            'parameters' => [
                'password' => $secretpassword,
                'database' => 10,
            ],
        ];
        */
    ];
        $client = new \Predis\Client($param);
        $client->connect();
    }

    /*
        $client = new Predis\Client([
        'scheme' => 'tls',
        'ssl'    => ['cafile' => 'private.pem', 'verify_peer' => true],
        ]);

        // Same set of parameters, but using an URI string:
        $client = new Predis\Client('tls://127.0.0.1?ssl[cafile]=private.pem&ssl[verify_peer]=1');
    */

    /*
        $parameters = ['tcp://10.0.0.1?alias=master', 'tcp://10.0.0.2', 'tcp://10.0.0.3'];
        $options    = ['replication' => function () {
            // Set scripts that won't trigger a switch from a slave to the master node.
            $strategy = new Predis\Replication\ReplicationStrategy();
            $strategy->setScriptReadOnly($LUA_SCRIPT);

            return new Predis\Connection\Aggregate\MasterSlaveReplication($strategy);
        }];

        $client = new Predis\Client($parameters, $options);
        $client->eval($LUA_SCRIPT, 0);             // Sticks to slave using `eval`...
        $client->evalsha(sha1($LUA_SCRIPT), 0);    // ... and `evalsha`, too.
    */

    function pipeline()
    {
        /*$responses = $client->pipeline(function ($pipe) {
            for ($i = 0; $i < 1000; $i++) {
                $pipe->set("key:$i", str_pad($i, 4, '0', 0));
                $pipe->get("key:$i");
            }
        });*/
    }

    function transact()
    {
        /*
        $responses = $client->transaction(function ($tx) {
            $tx->set('foo', 'bar');
            $tx->get('foo');
        });

        // Returns a transaction that can be chained thanks to its fluent interface:
        $responses = $client->transaction()->set('foo', 'bar')->get('foo')->execute();
        */
    }

    function script()
    {
        /*
         *     public function getScript()
    {
        return <<<LUA
        math.randomseed(ARGV[1])
        local rnd = tostring(math.random())
        redis.call('lpush', KEYS[1], rnd)
        return rnd
        LUA;
            }
        }

        // Inject the script command in the current profile:
        $client = new Predis\Client();
        $client->getProfile()->defineCommand('lpushrand', 'ListPushRandomValue');

        $response = $client->lpushrand('random_values', $seed = mt_rand());
         */
    }

    function connect()
    {
        /*
         * $client = new Predis\Client('tcp://127.0.0.1', [
            'connections' => [
                'tcp'  => 'Predis\Connection\PhpiredisStreamConnection',  // PHP stream resources
                'unix' => 'Predis\Connection\PhpiredisSocketConnection',  // ext-socket resources
            ],
        ]); 

        class MyConnectionClass implements Predis\Connection\NodeConnectionInterface
        {
            // Implementation goes here...
        }

        // Use MyConnectionClass to handle connections for the `tcp` scheme:
        $client = new Predis\Client('tcp://127.0.0.1', [
            'connections' => ['tcp' => 'MyConnectionClass'],
        ]);
         */
    }
}

```
redis:
      image: redis:alpine
      #container_name: phalcontesting-redis
      ports:
        - 6379:6379
      volumes:
        - /TMAID/PH/phpdocker/redis/redis.conf:/usr/local/etc/redis/redis.conf
        - /TMAID/PH/application/data:/data
      command: ["redis-server", "/usr/local/etc/redis/redis.conf"]
    
    redis_stl:
      image: redis:alpine
      environment:
        - REDIS_MASTER:redis
      volumes:
        - /TMAID/PH/phpdocker/redis/sentinel.conf:/usr/local/etc/redis/sentinel.conf
      #container_name: phalcontesting-redis
      ports:
        - 26379:26379
      command: ["redis-sentinel", "/usr/local/etc/redis/sentinel.conf"] 
    redis_slv:
      image: redis:alpine
      #container_name: phalcontesting-redis
      volumes:
        - /TMAID/PH/phpdocker/redis/redis.conf:/usr/local/etc/redis/redis.conf
      ports:
        - 6380-6385:6379
      command: ["redis-server", "--slaveof redis 6379"]
      #scale: 2
```